/*Library*/
#include <Arduino.h>
#include <math.h>
#include <Wire.h>
#include <Adafruit_MLX90614.h>
#include "MAX30100_PulseOximeter.h"
#include <SoftwareSerial.h>

/*initialization sensor*/
Adafruit_MLX90614 mlx = Adafruit_MLX90614();
PulseOximeter pox;

/*xbee transmisi*/
#define rxPin 19
#define txPin 18
SoftwareSerial xbee = SoftwareSerial(rxPin, txPin);

const long interval_temperatur = 10000;
unsigned long prev_temperatur = 0;

#define REPORTING_PERIOD_MS     1000
uint32_t tsLastReport = 0;

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
    Serial.println("Beat!");
    return true;
}

void setup() {
  Wire.begin();
  Serial.begin(9600);
  xbee.begin(9600);
  pox.begin();
  mlx.begin();  
 
  pox.setOnBeatDetectedCallback(onBeatDetected); 
}

void loop() {
  pox.update();
  if(millis() - tsLastReport > REPORTING_PERIOD_MS){
    tsLastReport = millis();
    bacaSensorDetak();
  }
 
  bacaSensorSuhu();
  
  sekarang = millis();

  int stat;
  if(sekarang - temp > 6000){
      Serial.println("Hasil Pemantauan :");
      Serial.print(namaNode+" ");
      Serial.print("BPM : " + String(detak) + "bpm | ");
      Serial.print("Sa02 : " + String(oksigen) + "% | ");
      Serial.print("Suhu : " + String(suhu) + "*c ");
      if(detak!=0 && suhu !=1037 && oksigen !=0){
        stat = 1;
      }else{
        stat = 0;
      }
      Serial.print("Status : " + String(stat));
      Serial.println();
      pesan = namaNode + "|" + detak + "|" + oksigen + "|" + suhu + "|" + stat + '\n';
      xbee.print(pesan);
      temp = sekarang;
  }
}
  
void bacaSensorDetak(){
  detak = pox.getHeartRate();
  oksigen = pox.getSpO2();
}

void bacaSensorSuhu(){
  unsigned long curr_temperatur = millis();

  if(curr_temperatur - prev_temperatur >= interval_temperatur){
      prev_temperatur = curr_temperatur;
      suhu = mlx.readObjectTempC();
  }
}