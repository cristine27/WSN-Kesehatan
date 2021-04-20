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

int temp = 0; //temp waktu
int sekarang = 0;//waktu sekarang

String pesan = "";
String namaNode = "node1";

// dipanggil jika terdapat detak yang terdeteksi
bool onBeatDetected()
{
    return true;
    Serial.println("Beat!");
}

//void checkStatus(){
//    if(!pox.begin()) {
//      Serial.println("FAILED");
//      for(;;);
//    } else {
//      isDetakNyala = true;
//      Serial.println("SUCCESS");
//    }
//        
//    if(!mlx.begin()){
//      Serial.println("MLX90164 FAILED");
//      for(;;);
//    } else {
//      isTempNyala = true;
//      Serial.println("SUCCESS");
//    }
//}

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
  
//  checkStatus();

  pox.setOnBeatDetectedCallback(onBeatDetected);
  checkStatus();
}

void loop() {
  // put your main code here, to run repeatedly:  
  if(xbee.available()){
    byte temp = xbee.read();
    if(temp=='a'){
      Serial.println(temp);
    }
  }
  
  /*tanpa menggunakan trigger serial*/
  bacaSensorDetak();
  bacaSensorSuhu();
  int stat = enkapStatus();
  
  sekarang = millis();
//  namaNode1 = "test";
//  detak = 10;
//  oksigen = 10;
//  suhu = 1.1;
//  pesan = namaNode1 + "|" + detak + "|" + oksigen + "|" + suhu;

//  if(isMlxOn || isMaxOn){
//    stat = 1;
//  }
  
  if(sekarang - temp > 7000){
      Serial.println("Hasil Pemantauan :");
      Serial.print(namaNode+" ");
      Serial.print("BPM : " + String(detak) + "bpm | ");
      Serial.print("Sa02 : " + String(oksigen) + "% | ");
      Serial.print("Suhu : " + String(suhu) + "*c");
      Serial.print("Status : " + String(stat));
      Serial.println();
      pesan = namaNode + "|" + detak + "|" + oksigen + "|" + suhu + "|" + stat + '\n';
      xbee.print(pesan);
      temp = sekarang;
      
  }
  
  }

  /*menggunakan trigger serial
  bacaSensorDetak();
  
  if(Serial.available()>0){
    bacaSensorSuhu();
    
    if(Serial.read()=='1'){
      if(temp>0 && temp >10){
        Serial.println("Hasil Pemantauan :");
        Serial.print(namaNode1+" ");
        Serial.print("BPM : " + String(detakTo) + "bpm | ");
        Serial.print("Sa02 : " + String(oksigenTo) + "% | ");
        Serial.print("Suhu : " + String(suhu) + "*c");
        Serial.println();

        detakTo = 0;
        oksigenTo = 0;
        temp = 0;
      } else{
        Serial.println("Mohon Tunggu Sebentar.."); 
      }
    }else{
      Serial.println("error~");
    }
  }*/



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
//      Serial.print("*C\tObject = "); Serial.print(mlx.readObjectTempC()); Serial.println("*C");
  }
}

void matikanSensorDetak(){
  pox.shutdown();
}

void nyalakanSensorDetak(){
  pox.resume();
}

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
