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
counter = 15
insertDataPasien = True
idPasien = 0
statusPeriksa = False

# dictionary untuk mapping pasien dengan node yang digunakan 
# misal node1 : pasienX
# dictionary key akan di masukkan terlebih dahulu 
Pasien = {
    
}

# dictionary untuk mapping status pasien,node apakah valid
# 1 = valid, 2 = node offline, 3 = id tidak ditemukan
# misal 
# [0] = 1
StatusInput = {

}

# dictionary untuk simpan status node online atau offline
# bisa jadi uda ga butuh, krna bisa pake db node lgsg select
Node = {

}

Parameter = {

}

idN = {

}

MapNodeParam = {

}

global hasilError

# initial serial
s = serial.Serial(
    port='/dev/ttyUSB0',
    baudrate=9600,
    parity=serial.PARITY_NONE,
    stopbits=serial.STOPBITS_ONE,
    bytesize=serial.EIGHTBITS,
    timeout=3
)

# default tampilan
print("Aplikasi Base Station Berjalan")
print("----------------------")
print("Daftar Menu Perintah : ")
print("1. Mulai Pemeriksaan")
print("2. Cek Status Node")
print("3. Daftar Node Baru")
print("4. Daftar Parameter Pemantauan Baru")
print("5. Assign Parameter ke Node")
print("6. Keluar dari Aplikasi")
print("----------------------")
print("Silahkan Input Nomor Perintah : ")

perintah = input()

def mainMenu():
    print("Aplikasi Base Station Berjalan")
    print("----------------------")
    print("Daftar Menu Perintah : ")
    print("1. Mulai Pemeriksaan")
    print("2. Cek Status Node")
    print("3. Daftar Node baru")
    print("4. Daftar Parameter Pemantauan Baru")
    print("5. Assign Parameter ke Node")
    print("6. Keluar dari Aplikasi")
    print("----------------------")
    print("Silahkan Input Nomor Perintah : ")
    print("")
    
def mapNodeName():
    db = mysql.connector.connect(
            host='localhost',
            database='WSN',
            user='phpmyadmin',
            password='raspberry',
            pool_name='mypool',
            pool_size=POOL_SIZE+1
    )
    
    cursor = db.cursor(buffered=True)
    
    cursor.execute("SELECT namaNode,idNode from node")
    
    res = cursor.fetchall()
    
    for x in res:
        nama = x[0]
        temp = x[1]
        if nama not in Pasien.keys() and nama not in Node.keys() and nama not in idN.keys():
            Pasien[nama] = 0
            Node[nama] = "offline"
            idN[nama] = temp

    cursor.execute("SELECT idParameter,namaParameter from parameter")

    res = cursor.fetchall()

    for x in res: 
        idParameter = x[0]
        namaParameter = x[1]
        namaParameter.lower()
        if namaParameter not in Parameter.keys():
            Parameter[namaParameter] = idParameter

    sql = "SELECT node.namaNode as nama,parameter.namaParameter from memiliki JOIN node ON memiliki.idNode = node.idNode Join parameter ON memiliki.idParameter = parameter.idParameter"
    cursor.execute(sql)

    res = cursor.fetchall()

    for x in res:
        namaNode = x[0].lower()
        namaParam = x[1].lower()
        if namaNode not in MapNodeParam.keys():
            MapNodeParam.setdefault(namaNode,[])
        MapNodeParam[namaNode].append(namaParam)
    cursor.close()
    db.close()
    

    # testing isi dictionary
    #print(Pasien)
    #print(Node)
    #print(idNode)

def validateData(x):
    # print("masuk function validate")
    res = False
    potong = x.split("|")
    #isi data = namaNode | detak | oksigen | temperatur | status
    if len(potong) > 1:
        if(potong[0] != "" and potong[1] != 0 and potong[2] != 0 and potong[3] != 0 and potong[4] != -1):
            # print("masuk function validate")
            res = True
    return res

def getDataSense(x):
    potong = x.split("|")
    if validateData(x):
        node = potong[0]
        detak = potong[1]
        oksigen = potong[2]
        suhu = potong[3]
        statusNode = potong[4]

    waktu = datetime.datetime.now()
    waktu = waktu.strftime('%Y-%m-%d %H:%M:%S')

    return node, detak, oksigen, suhu, waktu

def konekDb():
    # connect to mysql database
    try:
        db = mysql.connector.connect(
            host='localhost',
            database='WSN',
            user='phpmyadmin',
            password='raspberry',
            pool_name='mypool',
            pool_size=POOL_SIZE+1
        )

        if db.is_connected():
            print("connected to MySQL database")

    except Error as e:
        print(e)

def hidupkanNode(namaNode):
    db = mysql.connector.connect(
        host='localhost',
        database='WSN',
        user='phpmyadmin',
        password='raspberry',
        pool_name='mypool',
        pool_size=POOL_SIZE+1
    )

    cursor = db.cursor(buffered=True)
    queryUpdate = "UPDATE node SET status = 1 WHERE namaNode = %s"
    val = (namaNode,)

    cursor.execute(queryUpdate, val)

    db.commit()
    cursor.close()
    db.close()

def matikanNode(namaNode):
    db = mysql.connector.connect(
        host='localhost',
        database='WSN',
        user='phpmyadmin',
        password='raspberry',
        pool_name='mypool',
        pool_size=POOL_SIZE+1
    )
    
    cursor = db.cursor(buffered=True)
    queryUpdate = "UPDATE node SET status = 0 WHERE namaNode = %s"
    val = (namaNode,)

    cursor.execute(queryUpdate, val)

    db.commit()
    cursor.close()
    db.close()

def getStatusNode(data):  
    status = False
    if validateData(data):
        potong = data.split("|")
        if(verifyidNode(potong[0])):
            Node[potong[0]] = "online"
            hidupkanNode(potong[0])
            status = True
    # print(Node)
    return status

def InsertDb(x):
    # konekDb()
    db = mysql.connector.connect(
        host='localhost',
        database='WSN',
        user='phpmyadmin',
        password='raspberry',
        pool_name='mypool',
        pool_size=POOL_SIZE+1
    )

    cursor = db.cursor(buffered=True)
    # hasil get data dari arduino
    node = str(x[0])
    detak = str(x[1])
    oksigen = str(x[2])
    suhu = str(x[3])
    waktu = x[4]
    
    # convert data sebelum masuk ke db
    node = str(node)
    idPasien = Pasien.get(node)
    idNode = idN.get(node)
    # idNode = "".join(map(str, idN.get(node,None)))
    # convert data
    detak = str(detak)
    oksigen = str(oksigen)
    suhu = str(suhu)
    
    queryInsert = (
        "INSERT INTO periksa (idPasien, idNode, waktu, hasil1, hasil2, hasil3)"
        "VALUES (%s, %s, %s, %s, %s, %s)"
    )

    values = (idPasien, idNode, waktu, detak, oksigen, suhu)
    
    
    #commit query sql
    cursor.execute(queryInsert, values)
    db.commit()

    cursor.close()
    db.close()
    
def verifyidPasien(idPasien):
    db = mysql.connector.connect(
        host='localhost',
        database='WSN',
        user='phpmyadmin',
        password='raspberry',
        pool_name='mypool',
        pool_size=POOL_SIZE+1
    )
    isValid = False
    cursor = db.cursor(buffered=True)
    
    query = "Select idPasien FROM pasien where idPasien=%s"
    value = (idPasien,)
    cursor.execute(query,value)
    
    res = cursor.fetchall()
    
    for x in res:
        temp = "".join(map(str,x))
        if temp == idPasien:
            isValid = True
    
    return isValid

def verifyidNode(namaNode):
    isValid = True

    if idN.get(namaNode)==None:
        isValid = False
    
    return isValid

def insertDataNodePasien(data,jumlahPasien):
    global hasilError
    pisah = data.split("|")
    i = 0
    while(i<jumlahPasien):
        temp = True
        potong = pisah[i].split(",")
        if(len(potong)>0):
            idP = potong[0]
            namaNode = potong[1]
            if(idP!=0 and Node!=""):
                if(verifyidPasien(idP) and verifyidNode(namaNode)):
                    temp = Node.get(namaNode)
                    if temp =="online" or temp == "1":
                        # masukan idPasien ke dalam dictionary dengan key NamaNode
                        Pasien[namaNode] = idP
                        StatusInput[i] = 1
                    else:
                        StatusInput[namaNode] = 2
                else:
                    StatusInput[i] = 3
                    hasilError = idP + "," + namaNode

        i = i + 1

def checkIfAttached(x):
    check = False
    potong = x.split("|")
    if(potong[len(potong)-1]=='0'):
        check = True
    return check

def insertNode(namaNode):
    flag = True
    
    if verifyidNode(namaNode):
        flag = False
        print("Maaf silahkan input nama node lain")
        print("Nama node tidak boleh duplikat")
    else:
        db = mysql.connector.connect(
            host='localhost',
            database='WSN',
            user='phpmyadmin',
            password='raspberry',
            pool_name='mypool',
            pool_size=POOL_SIZE+1
        )

        cursor = db.cursor(buffered=True)

        #convert data
        
        queryInsert = (
            "INSERT INTO node (namaNode, status)"
            "VALUES (%s, %s)"
        )

        values = (namaNode,1)
        
        #commit query sql
        cursor.execute(queryInsert, values)
        # print("execute query")
        db.commit()

        cursor.close()
        db.close()
    return flag

def insertParameter(namaParameter):
    namaParameter.lower()
    flag = True
    
    if Parameter.get(namaParameter)==None:
        db = mysql.connector.connect(
            host='localhost',
            database='WSN',
            user='phpmyadmin',
            password='raspberry',
            pool_name='mypool',
            pool_size=POOL_SIZE+1
        )

        cursor = db.cursor(buffered=True)

        #convert data
        
        queryInsert = (
            "INSERT INTO parameter (namaParameter)"
            "VALUES (%s)"
        )

        values = (namaParameter,)
        
        #commit query sql
        cursor.execute(queryInsert, values)
        # print("execute query")
        db.commit()

        cursor.close()
        db.close()
    else:  
        flag = False
    return flag

def assignNodeParam(namaNode,param):
    flag = True
    isParamEx = False
    db = mysql.connector.connect(
            host='localhost',
            database='WSN',
            user='phpmyadmin',
            password='raspberry',
            pool_name='mypool',
            pool_size=POOL_SIZE+1
        )

    cursor = db.cursor(buffered=True)

    if verifyidNode(namaNode):
        idNode = idN.get(namaNode)
        print(Parameter.get(param))#ini untuk dapatin idParameternya
        for key,value in MapNodeParam.items():
            if key == namaNode and value == param:
                isParamEx = True
        if ((Parameter.get(param)!=None) and (not isParamEx)):
            idParam = Parameter.get(param)

            queryInsert = (
            "INSERT INTO memiliki (idNode, idParameter)"
            "VALUES (%s, %s)"
            )

            values = (idNode,idParam)
        
            #commit query sql
            cursor.execute(queryInsert, values)
            
            db.commit()
            cursor.close()
            db.close()
            print("Selamat assign parameter ke node berhasil..")
        else:
            flag = False
            if isParamEx:
                print("Maaf parameter sudah terdaftar pada node")
            else:
                print("Maaf parameter belum terdaftar mohon daftar terlebih dahulu")
            # mainMenu()
    else:
        flag = False
        print("Maaf nama node yang dimasukkan tidak terdaftar")
    return flag

class statusN():
    def __init__(self, interval=1):
        self.interval = interval

        sensingThread = threading.Thread(target=self.run, args=())
        sensingThread.daemon = True

        sensingThread.start()

    def run(self):
        counter = 0
        while counter<=5:
            counter = counter + 1
            msg = s.readline().decode("ascii").strip()
            getStatusNode(msg)
            
            if counter==5:
                break

            time.sleep(self.interval)

class periksa():
    statusPeriksa = False
    def __init__(self, interval=3):
        self.interval = interval

        sensingThread = threading.Thread(target=self.run, args=())
        sensingThread.daemon = True

        sensingThread.start()

    def run(self):
        counter = 0
        while sensing and counter<=15:
            counter = counter + 1
            print("Countdown Pemeriksaan : " , 15-counter)
            msg = s.readline().decode("ascii").strip()
            status = checkIfAttached(msg)
            if status==False:                           
                data = getDataSense(msg)
                if data != None:
                    InsertDb(data)
            
            if counter==15:
                print("Pemeriksaan Telah Selesai")
                break

            time.sleep(self.interval)
        mainMenu()

# jumlah threads(jumlah max req dari dari app)
POOL_SIZE = 20

while appRunning:
    mapNodeName()
    while menuShow: 
        # statusN()
        print(" ")
        if(perintah == "1"):
            statusN()
            counter = 0
            print("Silahkan Masukkan Jumlah Pasien yang Akan di Periksa: ")
            print("")
            jumlahNode = len(Node)
            jumlahPasien = int(input())
            if(jumlahPasien>jumlahNode):
                print("Maaf jumlah node yang dapat digunakan paralel adalah " , jumlahNode)
                print("Silahkan input ulang")
                mainMenu()
            else:
                print("Silahkan Masukkan idPasien yang akan di Periksa oleh Tiap Node: ")
                print("Format Penulisan : idPasien1,namaNode|idPasien2,namaNode|idPasien3,namaNode|")
                print("")
                formatPasien = input()
                cekFormat = formatPasien.split("|")
                if(len(cekFormat)<jumlahPasien or len(cekFormat.split(","))<2) :
                    print("Maaf input salah")
                    mainMenu()
                else:
                    print("Mohon tunggu Assign pasien sedang dilakukan..")
                    
                    insertDataNodePasien(formatPasien,jumlahPasien)
                    
                    flag = False
                    j = 0
                    for key,value in StatusInput.items():
                        if value == 2:
                            print("Maaf saat ini ", key, " sedang offline")
                            print("")
                        elif value == 3:
                            j = j + 1
                        else:
                            flag = True
                    if flag:
                            print("Assign Pasien pada Node Berhasil")
                            print(Pasien)
                            print("Pemeriksaan sedang dilakukan mohon tunggu...")
                            periksa()
                    else:
                        if j>1:
                            print("kedua input nama node atau idPasien salah")
                        else:
                            print("data input node atau idPasien salah")
                        print("Silahkan check data kembali")
                        mainMenu()
                
                
                # while sensing and counter<=5:
                #     counter = counter + 1
                #     # msg = s.readline().decode("ascii").strip()
                #     with concurrent.futures.ThreadPoolExecutor(max_workers=5) as executor:
                #         msg = s.readline().decode("ascii").strip()
                #         # print(counter)
                #         #lakukan pengecekan apakah sensor terpasang dengan benar pada tubuh pasien
                #         time.sleep(1)#
                #         status = checkIfAttached(msg)
                #         time.sleep(1)
                #         if status==False:
                #             time.sleep(1)#                            
                #             future = executor.submit(getDataSense, msg)
                #             time.sleep(1)
                #             data = future.result()
                #             # time.sleep(5)#
                            
                #             # time.sleep(5)
                #             if future.done() and data != None:
                #                 future2 = executor.submit(InsertDb, data)
                            
                #         elif status==True:
                #             print("Sensor Tidak Terpasang dengan Baik, Silahkan Periksa Kembali Perangkat..")
                    # if counter==5:
                    #     counter = 0
                    #     print("Pemeriksaan Telah Selesai")
                    #     break
                # counter = 0
                
                # mainMenu()

        #cek status node
        elif perintah == "2":
            print("Mohon menunggu..")
            counter = 0
            while counter<=5:
                counter = counter + 1
                msg = s.readline().decode("ascii").strip()
                getStatusNode(msg)
            
            if counter==5:
                break
            for key,value in Node.items():
                print(key, ' : ', value)
            mainMenu()

        #daftar node baru
        elif perintah == "3":
            print("Silahkan input namaNode : ")
            namaNode = input()
            namaNode.lower()
            flag = insertNode(namaNode)
            if flag:
                print("Apakah node ingin diassign ke parameter pemantauan ?")
                print("Ketik 1 jika ingin melanjutkan dan 0 jika tidak")
                masukkan = int(input())
                if masukkan == 1:
                    print("Satu node hanya dapat memiliki 3 parameter")
                    print("Berapa parameter yang ingin anda assign ? ")
                    jumlahParam = int(input())
                    if jumlahParam>3:
                        print("Maaf parameter hanya dapat 3")
                        mainMenu()
                    elif jumlahParam>0 and jumlahParam<=3:
                        print("Parameter yang tersedia : ")
                        for key,value in Parameter.items():
                            print("-" , value , key)
                        while(jumlahParam>0):
                            print("Silahkan input nama parameter : ")
                            namaParameter = input()
                            namaParameter.lower()
                            assignNodeParam(namaNode,namaParameter)
                            jumlahParam = jumlahParam - 1
                else:
                    mainMenu()
            else:
                mainMenu()

        #daftar parameter baru
        elif perintah == "4":
            print("Parameter yang telah tersedia : ")
            for key,value in Parameter.items():
                print("-" , value , key)
            print("Silahkan input nama parameter : ")
            namaParameter = input()

            flag = insertParameter(namaParameter)
            if flag:
                print("Selamat parameter baru berhasil ditambahkan..")
            else:
                print("Maaf parameter telah tersedia")
            mainMenu()

        #assign param ke node
        elif perintah == "5":
            print("Silahkan input nama node : ")
            namaNode = input()
            namaNode.lower()
            assignedParam = 0
            if(verifyidNode(namaNode)):
                assignedParam = len(MapNodeParam[namaNode])
                
                if assignedParam == 3: 
                    print("Maaf ", namaNode, "telah memiliki 3 parameter")
                elif assignedParam < 3 and assignedParam > 0:
                    temp = 3 - assignedParam
                    print("Satu node hanya dapat memiliki 3 parameter")
                    print("Jumlah parameter yang dapat diassign : ", temp)
                    print("Berapa parameter yang ingin anda assign ? ")
                    jumlahParam = int(input())
                    if jumlahParam>temp:
                        print("Maaf parameter hanya dapat 3")
                        mainMenu()
                    elif jumlahParam<temp or jumlahParam==temp:
                        print("Parameter yang tersedia : ")
                        for key,value in Parameter.items():
                            print("-" , value , key)
                        while(jumlahParam>0):
                            print("Silahkan input nama parameter : ")
                            namaParameter = input()
                            namaParameter.lower()
                            assignNodeParam(namaNode,namaParameter)
                            jumlahParam = jumlahParam - 1
            else:
                print("Maaf node tidak terdaftar silahkan periksa kembali")
            mainMenu()

        # turn off basestation
        elif perintah == "6":
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


