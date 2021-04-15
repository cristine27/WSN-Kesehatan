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
db = None

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
print("3. Keluar dari Aplikasi")
print("4. Stop Pemeriksaan")
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
        if(potong[0] != "" and potong[1] != 0 and potong[2] != 0 and potong[3] != 0):
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
        statusSensing = potong[5]
        # print("masuk if getdata")
        # print(node)

    waktu = datetime.datetime.now()
    # print(localtime)
    waktu = waktu.strftime('%Y-%m-%d %H:%M:%S')
    # print("masuk function getData")
    # print("data = ")
    # print(node, detak, oksigen, suhu, waktu)
    return node, detak, oksigen, suhu, waktu


# jumlah threads(jumlah max req dari dari app)
POOL_SIZE = 10


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


def getPingNode(x):
    potong = x.split("|")
    if validateData:
        node = potong[0]
        detak = potong[1]
        oksigen = potong[2]
        suhu = potong[3]
        statusNode = potong[4]

        waktu = datetime.datetime.now()
        waktu = waktu.strftime('%Y-%m-%d %H:%M:%S')

        return node, statusNode, waktu


def counterStart():
    global statusNode
    enter - "try : "

    if counter > 30:
        print("Node Offline")
        print("")
        statusNode = False

    return enter


def setDefCounter():
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
        "INSERT INTO data (waktu, node, detak, oksigen, suhu)"
        "VALUES (%s, %s, %s, %s, %s)"
    )

    values = (waktu, node, detak, oksigen, suhu)

    print("query")

    cursor.execute(queryInsert, values)

    print("execute query")
    # commit data ke database
    db.commit()

    cursor.close()
    db.close()


while appRunning:
    while menuShow:
        print(" ")
        if(perintah == "2"):
            s.write(str.encode("a"))
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
            print("Mengirim perintah check status")
            print("Respon akan diberikan dalam beberapa saat, mohon menunggu.")

            s.write(str.encode("b").strip())

            while(counter < 30):
                respon = s.readline().decode().strip()
                with concurrent.futures.ThreadPoolExecutor() as executor:
                    counterStart()
                    counter += 1
                    future3 = executor.submit(checkArduinoStatus, respon)

                    data
                    if future3.done() and future3.result() != None:
                        print("")
                        print("Hasil Check Status Node")
                        print(future3.result())
                        future4 = executor.submit(
                            getPingNode, future3.result())
                        global statusNode
                        statusNode = True
                        respon += 1

            if respon == 0:
                print(" ")
                print("Node Tidak Merespon")
                print("Silahkan Check Perangkat")
                print(" ")
            else:
                print(" ")
                print("Check Node Selesai")
                print(" ")
            defaulCounter()
            respon = 0
            mainMenu()

        # turn off base station
        elif perintah == "3":
            s.write(str.encode("c").strip())
            finding = False

            respon = s.readline().decode("ascii").strip()
            # with concurrent.futures.ThreadPoolExecutor() as executor:
            #     # future = executor.submit(goingOffline,respon)

            print("Mematikan Program Base Statsion")
            os.system("Receiver.py")
            print("================================")
            print("Sensing Telah Dihentikan")
            print("Base Station Offline")
            exit()

        # turn off sensing
        elif perintah == "4":
            s.write(str.encode("c").strip())
            finding = False

            print("Sensing telah Dihentikan !")
            statusSensing = False

            respon = s.readlin().decode("ascii").strip()
            with concurrent.futures.ThreadPoolExecutor() as executor:
                future = executor.submit(updateStatusSensing, respon)

            mainMenu()

        else:
            print("Input Perintah Salah")
            print("Restart Aplikasi")
            exit()

        perintah = input()
