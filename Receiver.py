#Base-station Code

import concurrent.futures
import datetime
import serial
import RPi.GPIO as GPIO
import mysql.connector
from mysql.connector import Error

GPIO.setmode(GPIO.BCM)
GPIO.setwarnings(False)

ser = serial.Serial(
        port = '/dev/ttyS0',
        baudrate = 9600,
        timeout = 1
)

while 1:
        print("test")
