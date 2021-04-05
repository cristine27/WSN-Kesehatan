/*Library*/
#include <Arduino.h>
#include <math.h>
#include <Wire.h>
#include <Adafruit_MLX90614.h>
#include "MAX30100.h"
#include <SoftwareSerial.h>

/*initialization sensor*/
MAX30100* pulseOxymeter;
Adafruit_MLX90614 mlx = Adafruit_MLX90614();

/*xbee transmisi*/
#define rxPin 19
#define txPin 18
SoftwareSerial xbee = SoftwareSerial(rxPin, txPin);

const long interval_temperatur = 2000;
const long interval_detak = 500;
long prev_temperatur = 0;

/*variabel*/
int powerPin = 2;
 
float suhu = 0.0; //suhu tubuh
int detakTo = 0; //detak rata rata selama 20 detik
int oksigenTo = 0; //oksigen rata rata selama 20 detik
int valDetak = 0; //jumlah variabel detak valid
int valOk = 0; //jumlah variabel oksigen valid
int i = 0; //variabel looping
int temp = 0; //jumlah pulse yang terdeteksi

String namaNode1 = "Node 1";
String namaNode2 = "Node 2";

void setup() {
  // put your setup code here, to run once:
  Wire.begin();
  Serial.begin(9600);
  xbee.begin(9600);

  mlx.begin();
  pulseOxymeter = new MAX30100();
  
/*Test kerja sensor*/
  Serial.println("Pulse oxymeter test!");
  Serial.println("Adafruit MLX90164 test!");

  

/*menjadikan pin power sebagai output*/
  pinMode(powerPin, OUTPUT);

/*initialize digital pin LED_BUILTIN sebagai output*/
  pinMode(LED_BUILTIN, OUTPUT);

//  menjadikan default powerPin bernilai LOW
  digitalWrite(powerPin, LOW);
}

void loop() {
  // put your main code here, to run repeatedly:  

  /*tanpa menggunakan trigger serial*/
  bacaSensorDetak();
  bacaSensorSuhu();
  if(temp>0 && temp >10){
    xbee.write("hai");
    xbee.println("Hasil Pemantauan :");
    xbee.print(namaNode1+" ");
    xbee.print("BPM : " + String(detakTo) + "bpm | ");
    xbee.print("Sa02 : " + String(oksigenTo) + "% | ");
    xbee.print("Suhu : " + String(suhu) + "*c");
    xbee.println();

    Serial.println("Hasil Pemantauan :");
    Serial.print(namaNode1+" ");
    Serial.print("BPM : " + String(detakTo) + "bpm | ");
    Serial.print("Sa02 : " + String(oksigenTo) + "% | ");
    Serial.print("Suhu : " + String(suhu) + "*c");
    Serial.println();
    
    detakTo = 0;
    oksigenTo = 0;
    temp = 0;
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
}


void bacaSensorDetak(){
  digitalWrite(powerPin, HIGH);
  int detak = 0;
  int oksigen = 0;
  
  pulseoxymeter_t result = pulseOxymeter->update();
  
  if( result.pulseDetected == true )
    {
//      Serial.println("masuk detak");
      temp++;
      for(i=0; i<20; i++){
        detak = result.heartBPM;
        oksigen = result.SaO2;
        
        if(detak>0 && detak<100 && detak>50){
          valDetak++;
          detakTo += detak;  
        }

        if(oksigen>0 && oksigen<100 && oksigen>90){
          valOk++;
          oksigenTo += oksigen;
        }  
      }
      detakTo = detakTo/valDetak;
      oksigenTo = oksigenTo/valOk;
      valDetak = 0;
      valOk = 0;
     }
     delay(10);
     digitalWrite(powerPin, LOW);  
}

void bacaSensorSuhu(){
//  Serial.println("masuk suhu");
  unsigned long curr_temperatur = millis();

  if(curr_temperatur - prev_temperatur >= interval_temperatur){
    prev_temperatur = curr_temperatur;

    suhu = mlx.readObjectTempC();
  }
}
