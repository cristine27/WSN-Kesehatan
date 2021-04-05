/*Library*/
#include <Arduino.h>
#include <math.h>
#include <Wire.h>
#include <Adafruit_MLX90614.h>
#include "MAX30100_PulseOximeter.h"
#include <SoftwareSerial.h>

/*xbee transmisi*/
#define rxPin 19
#define txPin 18
SoftwareSerial xbee = SoftwareSerial(rxPin, txPin);

/*initialization sensor*/
PulseOximeter pox;
Adafruit_MLX90614 mlx = Adafruit_MLX90614();

/*Initial Setting*/
#define REPORTING_PERIOD_MS     1000
#define REPORTING_PERIOD_ML     2000

long prev_temperatur = 0;
uint32_t tsLastReport = 0;

/*variabel*/
bool isDetakNyala = false; //check apakah sensor max30100 nyala 
bool isTempNyala = false; //check apakah sensor mlx90164 nyala

float suhu = 0.0; //suhu tubuh
int detak = 0; //detak jantung
int oksigen = 0; //oksigen dalam darah
int i = 0; //variabel looping
int temp = 0; //jumlah pulse yang terdeteksi
String pesan = "";
String psn = "";
String namaNode1 = "Node 1";
String namaNode2 = "Node 2";

// dipanggil jika terdapat detak yang terdeteksi
void onBeatDetected()
{
    Serial.println("Beat!");
}

void checkStatus(){
  
    if(!mlx.begin()){
      Serial.println("MLX90164 FAILED");
      for(;;);
    }
    else {
      isTempNyala = true;
      Serial.println("SUCCESS");
    }
    
    if (!pox.begin()) {
      Serial.println("FAILED");
      for(;;);
    } else {
        isDetakNyala = true;
        Serial.println("SUCCESS");
    }
}

void setup() {
  // put your setup code here, to run once:
  Wire.begin();
  Serial.begin(9600);
  xbee.begin(9600);

  /*Test kerja sensor*/
  Serial.println("Pulse oxymeter test!");
  Serial.println("Adafruit MLX90164 test!");
  
//  checkStatus();
psn = "Node|1|2|3|4";
  xbee.print(psn);
//  pox.setOnBeatDetectedCallback(onBeatDetected);
}

void loop() {
  // put your main code here, to run repeatedly:  
  psn = "Node|1|2|3|4";
  xbee.print(psn);
  
  /*tanpa menggunakan trigger serial*/
  pox.update();
  bacaSensorDetak();
  bacaSensorSuhu();

  pesan = namaNode1 + "|" + detak + "|" + oksigen + "|" + suhu;
   
  Serial.println("Hasil Pemantauan :");
  Serial.print(namaNode1+" ");
  Serial.print("BPM : " + String(detak) + "bpm | ");
  Serial.print("Sa02 : " + String(oksigen) + "% | ");
  Serial.print("Suhu : " + String(suhu) + "*c");
  Serial.println();

  xbee.print(pesan);
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
  if (millis() - tsLastReport > REPORTING_PERIOD_MS) {
    detak = pox.getHeartRate();
    oksigen = pox.getSpO2();
    tsLastReport = millis();
  }
}

void bacaSensorSuhu(){
//  Serial.println("masuk suhu");
  if(millis() - prev_temperatur > REPORTING_PERIOD_ML){
    suhu = mlx.readObjectTempC();
    prev_temperatur = millis();
  }
}

void matikanSensorDetak(){
  pox.shutdown();
}

void nyalakanSensorDetak(){
  pox.resume();
}
