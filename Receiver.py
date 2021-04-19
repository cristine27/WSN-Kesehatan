# Base-station Code
from mysql.connector import Error
import mysql.connector  # connect python dengan mysql
import time
import serial  # akses serial port
import datetime  # waktu
import concurrent.futures  # threads
import threading

# variabel
appRunning = True
menuShow = True
sensing = True
counter = 0
insertDataPasien = True
idPasien = 0


global statusNode
global namaNode
Node = {
    "node1": "offline",
    "node2": "offline"
    }
# initial serial
s = serial.Serial(
    port='/dev/ttyUSB0',
    baudrate=9600,
    parity=serial.PARITY_NONE,
    stopbits=serial.STOPBITS_ONE,
    bytesize=serial.EIGHTBITS,
    timeout=1
)

# default tampilan
print("Aplikasi Base Station Berjalan")
print("----------------------")
print("Daftar Menu Perintah : ")
print("1. Check status Node")
print("2. Mulai Pemeriksaan")
print("3. Stop Pemeriksaan")
print("4. Keluar dari Aplikasi")
print("----------------------")
print("Silahkan Input Nomor Perintah : ")

perintah = input()
# print(perintah)


def mainMenu():
    print("masuk function mainMenu")
    print("Aplikasi Base Station Berjalan")
    print("----------------------")
    print("Daftar Menu Perintah : ")
    print("1. Check status Node")
    print("2. Mulai Pemeriksaan")
    print("3. Stop Pemeriksaan")
    print("4. Keluar dari Aplikasi")
    print("----------------------")
    print("Silahkan Input Nomor Perintah : ")


def validateData(x):
    # print("masuk function validate")
    potong = x.split("|")
    if len(potong) > 1:
        if(potong[0] != "" and potong[1] != 0 and potong[2] != 0 and potong[3] != 0 and potong[4] != -1):
            print("masuk function validate")
            return True


def getDataSense(x):
    potong = x.split("|")
    if validateData(x):
        node = potong[0]
        detak = potong[1]
        oksigen = potong[2]
        suhu = potong[3]
        statusNode = potong[4]
        # print("masuk if getdata")
        # print(node)

    waktu = datetime.datetime.now()
    # print(localtime)
    waktu = waktu.strftime('%Y-%m-%d %H:%M:%S')
    # print("masuk function getData")
    # print("data = ")
    # print(node, detak, oksigen, suhu, waktu)
    return node, detak, oksigen, suhu, waktu

def konekDb():
    # connect to mysql database
    try:
        db = mysql.connector.connect(
            host='localhost',
            database='coba',
            user='phpmyadmin',
            password='raspberry',
            pool_name='mypool',
            pool_size=POOL_SIZE+1
        )

        if db.is_connected():
            print("connected to MySQL database")

    except Error as e:
        print(e)


def updateStatusSensing(dataSensing):
    db = mysql.connector.connect(
        host='localhost',
        database='coba',
        user='phpmyadmin',
        password='raspberry',
        pool_name='mypool',
        pool_size=POOL_SIZE+1
    )

    waktu = datetime.datetime.now()
    waktu = waktu.strftime('%Y-%m-%d %H:%M:%S')
    TempStatus = 0

    # cursor = db.cursor(buffered=True)

    # queryNode2 = ("UPDATE nodesensor SET status")

def goingOffline(namaNode):
    db = mysql.connector.connect(
        host='localhost',
        database='coba',
        user='phpmyadmin',
        password='raspberry',
        pool_name='mypool',
        pool_size=POOL_SIZE+1
    )

    waktu = datetime.datetime.now()
    waktu = waktu.strftime('%Y-%m-%d %H:%M:%S')
    TempStatus = 0

    cursor = db.cursor(buffered=True)

    queryUpdate = ("UPDATE data SET status = %s, waktu = %s WHERE namaNode == %s")
    valueUpdate = (TempStatus, waktu, namaNode)

    cursor.execute(queryUpdate,valueUpdate)

    db.commit()
    cursor.close()
    db.close()


def getPingNode(x):
    potong = x.split("|")
    temp = ""
    if validateData(x):
        node = potong[0]
        status = potong[4]
    
    if(str(status)=="1"):
        temp = "online"
    else:
        temp = "offline"
        
    if(str(node)=="node1"):
        Node["node1"] = temp
    else:
        Node["node2"] = temp
    
    return node, status


def counterStart():
    global statusNode
    enter = "try : "

    if counter > 20:
        print("Node Offline")
        print("")
        statusNode = False

    return enter


def resetCounter():
    global counter
    counter = 0


def InsertDb(x):
    # konekDb()
    db = mysql.connector.connect(
        host='localhost',
        database='coba',
        user='phpmyadmin',
        password='raspberry',
        pool_name='mypool',
        pool_size=POOL_SIZE+1
    )

    cursor = db.cursor(buffered=True)
    # hasil get data dari arduino
    node = x[0]
    detak = x[1]
    oksigen = x[2]
    suhu = x[3]
    waktu = [4]
    print("cursor")

    # convert data sebelum masuk ke db
    node = str(node)
    detak = str(detak)
    oksigen = str(oksigen)
    suhu = str(suhu)
    waktu = str(waktu)
    print("convert")

    queryInsert = (
        "INSERT INTO data (idPasien, waktu, node, detak, oksigen, suhu, status)"
        "VALUES (%s, %s, %s, %s, %s, %s, %s)"
    )

    values = (idPasien, waktu, node, detak, oksigen, suhu)

    print("query")

    cursor.execute(queryInsert, values)

    print("execute query")
    # commit data ke database
    db.commit()

    cursor.close()
    db.close()
    
def verifyidPasien(idPasien,IdNode):
    db = mysql.connector.connect(
        host='localhost',
        database='coba',
        user='phpmyadmin',
        password='raspberry',
        pool_name='mypool',
        pool_size=POOL_SIZE+1
    )
    isValid = True
    cursor = db.cursor(buffered=True)
    
    cursor.execute("Select idPasien, IdNode FROM pemeriksaan")
    
    res = cursor.fetchall()
    
    for x in res:
        if x == "" or x == 0:
            print(x)
            isValid = False
    
    return isValid

def insertDataNodePasien(x):
    db = mysql.connector.connect(
        host='localhost',
        database='coba',
        user='phpmyadmin',
        password='raspberry',
        pool_name='mypool',
        pool_size=POOL_SIZE+1
    )
    
    cursor = db.cursor(buffered=True)
    potong = x.split(",")
    if(len(potong)>0):
        idP = potong[0]
        idNode = potong[1]
        if(idP!=0 and idNode!=""):
            if(verifyidPasien(idP,idNode)):
                """
                queryInsert = (
                    "INSERT INTO test (idPasien, idNode)"
                    "VALUES (%s, %s)"
                )
                
                values = (idPasien,idNode)

                print("query")

                cursor.execute(queryInsert, values)

                print("execute query")
                # commit data ke database
                db.commit()

                cursor.close()
                db.close()
                """
                global idPasien 
                idPasien = idP
                print("Assign Pasien pada Node Berhasil")
            else:
                print("Maaf idNode dan idPasien yang dimasukkan tidak ditemukan")
                print("Silahkan ulangi atau check data kembali)")
                global insertDataPasien
                insertDataPasien = False
                mainMenu()
                
            
    

# jumlah threads(jumlah max req dari dari app)
POOL_SIZE = 10

while appRunning:
    while menuShow:
        print(" ")
        if(perintah == "2"):
            s.write(str.encode("a"))
            
            print("Silahkan Masukkan Jumlah Pasien yang Akan di Periksa: ")
            jumlahPasien = int(input())
            
            #while(jumlahPasien>0):
            print("Silahkan Masukkan idPasien yang akan di Periksa oleh Tiap Node: ")
            print("Format Penulisan : idPasien1,namaNode")
            print("Penulisan dilakukan persatu pasien")
            isFinishInsertData = True
            while(jumlahPasien>0):
                formatPasien = input()
                insertDataNodePasien(formatPasien)
            
            if insertDataPasien:
                print("Pemeriksaan sedang dilakukan mohon tunggu...")
                print("Nama Node | detak Jantung | Oksigen | Suhu | Waktu ")
                while sensing:
                    # ambil data sensing arduino
                    msg = s.readline().decode("ascii").strip()
                    print("hasil sensing arduino : ")
                    print(msg)
                    counter = counter + 1
                    time.sleep(5)
                    with concurrent.futures.ThreadPoolExecutor(max_workers=1) as executor:
                        future = executor.submit(getDataSense, msg)
                        # if(future.done()):
                        print("future selesai")
                        time.sleep(1)
                        data = future.result()
                        print(data)

                        if future.done() and data != None:
                            print("masuk submit")
                            future2 = executor.submit(InsertDb, data)

        elif perintah == "1":
            #getPasien()
            print("Mengirim perintah check status")
            print("Respon akan diberikan dalam beberapa saat, mohon menunggu.")

            s.write(str.encode("b"))
            msg = s.readline().decode("ascii").strip()
            time.sleep(5)
            respon = 0
            while(counter < 20):
                #respon = 0
                with concurrent.futures.ThreadPoolExecutor(max_workers=2) as executor:
                    counterStart()
                    counter += 1
                    time.sleep(1)
                    future3 = executor.submit(getPingNode, msg)
                    time.sleep(1)
                    if future3.done() and future3.result() != None:
                        respon = 1
                        print("")
                        print("Hasil Check Status Node")
                        # future4 = executor.submit(, future3.result())
                        global statusNode
                        statusNode = True
                        print(future3.result())
            if respon == 1:
                print(" ")
                print(Node)
                print("Check Node Selesai")
                print(" ")
            elif(respon==0 and counter==20):
                print(" ")
                print(Node)
                print("Node Tidak Merespon")
                print("Silahkan Cek Perangkat")
                print(" ")
            resetCounter()
            respon = 0
            mainMenu()

        # turn off sensing dan base station
        elif perintah == "3":
            s.write(str.encode("c").strip())
            finding = False
            
            print("Silahkan masukkan jumlah node yang akan dimatikan :")
            jumlahNode = int(input())
            
            print("Silahkan masukkan nama node yang akan dimatikan : ")
            print("format penulisan : namaNode1,namaNode2")
            namaNode = input()
            
            temp = namaNode.split(",")
            print(len(temp))
            respon = s.readline().decode("ascii").strip()
            print(respon)
            while(jumlahNode>0):
                with concurrent.futures.ThreadPoolExecutor() as executor:
                    future = executor.submit(goingOffline,temp[jumlahNode-1])
                    jumlahNode = jumlahNode - 1
            print("Node berhasil dimatikan")
            mainMenu()
            
        # turn off basestation
        elif perintah == "4":
             s.write(str.encode("c").strip())
             print("Mematikan Program Base Statsion")
             # os.system("Receiver.py")
             print("================================")
             print("Sensing Telah Dihentikan")
             print("Base Station Offline")
             exit()


            

        else:
            print("Input Perintah Salah")
            print("Restart Aplikasi")
            exit()

        perintah = input()
