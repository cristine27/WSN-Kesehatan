/*Library*/
#include <Arduino.h>
#include <math.h>
#include <Wire.h>
#include <Adafruit_MLX90614.h>
#include "MAX30100_PulseOximeter.h"
#include <SoftwareSerial.h>

/*initialization sensor*/
PulseOximeter pox;
Adafruit_MLX90614 mlx = Adafruit_MLX90614();

/*xbee transmisi*/
#define rxPin 19
#define txPin 18
SoftwareSerial xbee = SoftwareSerial(rxPin, txPin);

const long interval_temperatur = 10000;
unsigned long prev_temperatur = 0;

/*variabel*/
bool isMlxOn = false; //check apakah sensor max30100 nyala 
bool isMaxOn = false; //check apakah sensor mlx90164 nyala

float suhu = 0.0; //suhu tubuh
int detak = 0; //detak jantung
int oksigen = 0; //oksigen dalam darah

int temp = 0; //temp
int sekarang = 0;//waktu sekarang

String pesan = "";
String namaNode = "node2";

// dipanggil jika terdapat detak yang terdeteksi
bool onBeatDetected()
{
    return true;
    Serial.println("Beat!");
}

void setup() {
  // put your setup code here, to run once:
  Wire.begin();
  Serial.begin(9600);
  xbee.begin(9600);

  /*Test kerja sensor*/
  Serial.println("Pulse oxymeter test!");
  Serial.println("Adafruit MLX90164 test!");
  pox.begin();//pox harus di letakkan sebelum mlx
  mlx.begin();  
  
  pox.setOnBeatDetectedCallback(onBeatDetected);
  checkStatus();
}

void loop() {
  // put your main code here, to run repeatedly:  
  if(xbee.available()){
    byte temp = xbee.read();
    if(temp=='a' || temp=='b'){
      Serial.println(temp);
    }
  }
  
  bacaSensorDetak();
  bacaSensorSuhu();
  int stat = enkapStatus();
  
  sekarang = millis();
  
  if(sekarang - temp > 6000){
      Serial.println("Hasil Pemantauan :");
      Serial.println("");
      Serial.print(namaNode+" ");
      Serial.print("BPM : " + String(detak) + "bpm | ");
      Serial.print("Sa02 : " + String(oksigen) + "% | ");
      Serial.print("Suhu : " + String(suhu) + "*c");
      Serial.print("Status : " + String(stat));
      Serial.println("");
      pesan = namaNode + "|" + detak + "|" + oksigen + "|" + suhu + "|" + stat + '\n';
      xbee.print(pesan);
      temp = sekarang;   
  }
}

void bacaSensorDetak(){
//  Serial.println("masuk detak");
  pox.update();
  if (onBeatDetected()) {
    detak = pox.getHeartRate();
    oksigen = pox.getSpO2();
  }
  delay(10);
}

void bacaSensorSuhu(){
  unsigned long curr_temperatur = millis();

  if(curr_temperatur - prev_temperatur >= interval_temperatur){
      prev_temperatur = curr_temperatur;
//      Serial.println("masuk suhu");
      suhu = mlx.readObjectTempC();
  }
}

//turn off max
void matikanSensorDetak(){
  pox.shutdown();
}

void nyalakanSensorDetak(){
  pox.resume();
}
//turn on max

//check status node apakah online atau tidak
boolean checkStatus(){
  boolean isActive = false;
  if(pox.begin()){//node temperatur
    isMaxOn = true;
  }
  if(mlx.begin()){//node detak
    isMlxOn = true;
  }

  if(isMlxOn || isMaxOn){
    isActive = true;
  }
  return isActive;
}

int enkapStatus(){
  int res = 0;
  if(checkStatus()){
    res = 1;
  }
  return res;
}
//mengembalikan status node
