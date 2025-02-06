import sys
import argparse
import xml.etree.ElementTree 
import re


# instrukcie
i3ops = ["ADD","SUB","MUL","IDIV","LT","GT","EQ","AND","OR","STRI2INT","CONCAT","GETCHAR","SETCHAR","JUMPIFEQ","JUMPIFNEQ"]
i2ops = ["NOT","MOVE","INT2CHAR","READ","STRLEN","TYPE"]
i1op = ["DEFVAR","CALL","PUSHS","POPS","WRITE","LABEL","JUMP","EXIT","DPRINT"]
i0op = ["CREATEFRAME","PUSHFRAME","POPFRAME","RETURN","BREAK"]



#Frames
TF= None
LF= None
GF= {}


dataStack = []
inCode = None
inContent = None

#kontrola argumentov
def parseArgs():
    argParser = ArgumentParser(description="argument parser")
    argsArray = {} 

    try:

        argParser.add_argument('--input', action='append')
        argParser.add_argument('--source', action='append')
        args = argParser.parse_args()  



        if (args.input is not None):
            if (len(args.input) > 1):
                argParser.error(77)
            argsArray["input"] = args.input[0]



        if (args.source is not None):
            if (len(args.source) > 1 or (not args.source[0])):
                argParser.error(77)
            argsArray["source"] = args.source[0]



    except:
        sys.exit(10) 

        
    return argsArray





# Vypise Help
def printHelpAndExit():

    print(

    '''
        pouzitie:
        python3.8 interpret.py <arguments>

            Arguments:

             1)  --help  |napoveda     
     
             2)  --source=<path>  |!Povinny argument! zadanie cesty zdrojoveho suboru, ak sa nezada bude nacitana z STDIN   
                        
             3)  --input=<path>  |!Povinny argument! zadanie cesty vstupneho suboru, ak sa nezada bude nacitana z STDIN  
                                     
    ''')

    sys.exit(0)




# lexikalna analyza  XML kodu pomocou kniznice xml.etree.ElementTree
def lex(code):

    try: tree = xml.etree.ElementTree.fromstring(code)

    except: 

        exit(31)



    if ('language' not in tree.attrib): # kontroluje tag : language ak nenajde => error 32

        sys.exit(32)

    if (tree.tag != 'program'):   # kontroluje tag : program ak nenajde => error 32
        sys.exit(32)

    if (tree.attrib['language'] != "IPPcode21"): # kontroluje atribut : languege-u (ci je za = IPPcode20) ak nenajde => error 32
       
        sys.exit(32)



    size = 1 

    if ('description' in tree.attrib):

        size+=1

    if ('name' in tree.attrib):

        size+=1



    if (len(tree.attrib) != size):

        sys.exit(32)



    eleDict = dict()



    for instr in tree: 

        if (instr.tag != "instruction"): # kontroluje tag : instruction ak nenajde => error 32

            sys.exit(32)



        if ((len(instr.attrib) != 2) or ('order' not in instr.attrib) or ('opcode' not in instr.attrib)): # kontroluje pocet atributov (=) : ak je ich viac ako 2 tak hlada tag order alebo opcode ak nenajde  => error 32

            sys.exit(32)



        order = int(instr.attrib['order'].strip())



        if (order in eleDict):

            sys.exit(32)

        eleDict[order] = {}
        eleDict[order] = instr.attrib
        eleDict[order]['args'] = {}

        

        argTags = {}
        for arg in instr:
            if arg.tag in argTags:
                sys.exit(32)
            argTags[arg.tag] = 1
            eleDict[order]['args'][arg.tag] = {}

            if (len(arg.attrib) != 1 or arg.attrib['type'] is None):

                sys.exit(32)



            if (arg.text is None):

                if arg.attrib['type'] == "string":

                    arg.text = ""

                else:

                    sys.exit(32)

            if ("#" in arg.text):

                sys.exit(32)

                    

            eleDict[order]['args'][arg.tag]['type'] = arg.attrib['type'].strip()
            eleDict[order]['args'][arg.tag]['value'] = arg.text.strip()           



    return eleDict

class ArgumentParser(argparse.ArgumentParser):
    def error(self, message):
        raise

# kontrola order parametrov (ci su kladne a ci sa neopakuju) ak nie je splnene => error 32
def checkOrder(c):

    array = []
    orderArray = []

    for x in range(1, len(c)+1):

        array.append(x)

    try:
        
        

        for value, key in c.items():

            if (int(key['order']) not in array):

                raise

        
                if (int(key['order']) in orderArray or int(key['order']) <= 0 ):
                            
                    raise

                orderArray.append(int(key['order']))
                

    except:

        sys.exit(32)
         

    return




#Regexi
Rlabel ="^(label@([a-zA-Z_\-$&%*!?][a-zA-Z0-9_\-$&%*!?]*))$"
Rvar = "^(var@)(GF|LF|TF)(@)([a-zA-Z_\-$&%*!?]+[a-zA-Z0-9_\-$&%*!?]*)$"
Rsym ="^(nil)(@)(nil)$|^(bool)(@)(true|false)$|^(int)(@)(\S+)$|^(string)(@)([\S]*)$|^(var@)(GF|LF|TF)(@)([a-zA-Z_\-$&%*!?]+[a-zA-Z0-9_\-$&%*!?]*)$"
Rtyp ="^type@(bool|int|string)$"

##
# kontrola instrukci pomocou re.match s vyssie definovanymi regexami ak je rozdiel => error 32

def checkRegex(args, argCodeWord):

    if (len(argCodeWord) == 11):
        if (argCodeWord == "Lab Sym Sym"):
            if (re.match(Rlabel, args[0]) is None):             #kontrola ci je 1. argument label
                sys.exit(32)

            

        elif (argCodeWord == "Var Sym Sym"):
            if (re.match(Rvar, args[0]) is None):               #kontrola ci je 1. argument var
                sys.exit(32) 

        

        if (re.match(Rsym, args[1]) is None or                  #kontrola ci je 2. a 3. argument sym
            re.match(Rsym, args[2]) is None):



            sys.exit(32)

    elif (len(argCodeWord) == 7):
        if (re.match(Rvar, args[0]) is None):                  #kontrola ci je 1. argument var (pri 2 argumentocch musi but vzdy prvy var)
            sys.exit(32)



        if (argCodeWord == "Var Sym"):
            if (re.match(Rsym, args[1]) is None):              #kontrola ci je 2. argument sym
                sys.exit(32)



        if (argCodeWord == "Var Typ"):
            if (re.match(Rtyp, args[1]) is None):              #kontrola ci je 2. argument typ
                sys.exit(32)

    elif (len(argCodeWord) == 3):
        if (argCodeWord == "Lab"):
            if (re.match(Rlabel, args[0]) is None):            #kontrola ci je 1. (jediny) argument label
                sys.exit(32)



        if (argCodeWord == "Var"):
            if (re.match(Rvar, args[0]) is None):              #kontrola ci je 1. (jediny) argument var
                sys.exit(32)



        if (argCodeWord == "Sym"):                             #kontrola ci je 1. (jediny) argument sym
            if (re.match(Rsym, args[0]) is None):
                sys.exit(32)

    else:
        sys.exit(32)




# kontroluje syntax parametru code ktory uz presiel lexerom a spracuje ho na tvar pre interpret
def syntax(code):
    checkOrder(code)
    interpretCode = {}

    

    for value, key in sorted(code.items()):
        key['opcode'] = key['opcode'].strip()
        if (len(key['args']) > 3 ):            # ziadna instrukcia nema viac ako 3 operandy
           sys.exit(32)
        interpretCode[key['order']] = key['order']
        interpretCode[key['order']] = {}
        interpretCode[key['order']][0] = key['opcode']



        if (key['opcode'] in i3ops):  #instrukcia s 3 operandami
            if (len(key['args']) != 3):
                sys.exit(32)



            if ('arg1' not in key['args'] or 'arg2' not in key['args'] or 'arg3' not in key['args']):

                sys.exit(32)



            typesAndValues = [key['args']['arg1']['type']+'@'+key['args']['arg1']['value'],
                              key['args']['arg2']['type']+'@'+key['args']['arg2']['value'],
                              key['args']['arg3']['type']+'@'+key['args']['arg3']['value']

                              ]

            if (key['opcode'] == "JUMPIFEQ" or key['opcode'] == "JUMPIFNEQ"):

                checkRegex(typesAndValues, "Lab Sym Sym")

            else:

                checkRegex(typesAndValues, "Var Sym Sym")



            interpretCode[key['order']][1] = typesAndValues[0]
            interpretCode[key['order']][2] = typesAndValues[1]
            interpretCode[key['order']][3] = typesAndValues[2]

            

        elif (key['opcode'] in i2ops): #instrukcia s 3 operandami
            if (len(key['args']) != 2):

                sys.exit(32)



            if ('arg1' not in key['args'] or 'arg2' not in key['args']):

                sys.exit(32)



            typesAndValues = [key['args']['arg1']['type']+'@'+key['args']['arg1']['value'],
                              key['args']['arg2']['type']+'@'+key['args']['arg2']['value']

                              ]

            if (key['opcode'] == "READ"):

                checkRegex(typesAndValues, "Var Typ")

            else:

                checkRegex(typesAndValues, "Var Sym")



            interpretCode[key['order']][1] = typesAndValues[0]
            interpretCode[key['order']][2] = typesAndValues[1]



        elif (key['opcode'] in i1op): #instrukcia s 1 operandom

            if (len(key['args']) != 1):

                sys.exit(32)



            if ('arg1' not in key['args']):

                sys.exit(32)



            typesAndValues = [key['args']['arg1']['type']+'@'+key['args']['arg1']['value']]
            if (key['opcode'] == "PUSHS" or key['opcode'] == "WRITE" or
                key['opcode'] == "EXIT" or key['opcode'] == "DPRINT"):

                

                checkRegex(typesAndValues, "Sym")
            elif(key['opcode'] == "CALL" or key['opcode'] == "LABEL" or
                 key['opcode'] == "JUMP"):



                checkRegex(typesAndValues, "Lab")

            else:

                checkRegex(typesAndValues, "Var")



            interpretCode[key['order']][1] = typesAndValues[0]



        elif (key['opcode'] in i0op):
            if (len(key['args']) != 0):
                sys.exit(32)

        else:

            sys.exit(32)

    data = list(interpretCode.values())
    for command in data:

        i = 0

        while i != len(command):
            if (command[i].count("@") == 2):
                command[i] = command[i].split("@")
                del command[i][0]
                command[i] = command[i][0]+"@"+command[i][1]

                

            i+=1  

    return data






#hlada premenu v zadanom ramci
# var - premenna ktoru hlada
# frame - raemc v ktorom sa hlada 
def findVariable(var, frame):
    global TF, LF, GF
    if (frame == None):
        sys.exit(55)



    if isinstance(frame, dict):
        for key, item in frame.items():
            if item[0] == var[1]:
                return item

    else:
        for item in frame:
            if (item[0] == var[1]):
                return item

    sys.exit(54)

# ziska typ a hodnotu zadaneho symolu
# Non fail safe: ak je true tak funkcia nevypise chybu ak je typ alebo value none
def getTypeAndValue(sym, NoneFailSafe=False):

    try:
        if sym[0] == "TF":
            value = findVariable(sym, TF)[2]
            type = findVariable(sym, TF)[1]

        elif sym[0] == "LF":
            value = findVariable(sym, LF[-1])[2]
            type = findVariable(sym, LF[-1])[1]

        elif sym[0] == "GF":
            value = findVariable(sym, GF)[2]
            type = findVariable(sym, GF)[1]

        else:

            value = sym[1]
            type = sym[0]
    except (TypeError, IndexError, AttributeError):

        sys.exit(55)



    if (NoneFailSafe is False and (type is None or value is None)):

        sys.exit(56)



    return type, value


def updateVar(var, type, value):

    try:

        if var[0] == "TF":

            index = TF.index(findVariable(var, TF))

            TF[index][1] = type

            TF[index][2] = value

        elif var[0] == "LF":

            index = LF[-1].index(findVariable(var, LF[-1]))

            LF[-1][index][1] = type

            LF[-1][index][2] = value

        elif var[0] == "GF":

            index = findVariable(var, GF)

            GF[GF[index[0]][0]][1] = type

            GF[GF[index[0]][0]][2] = value

        else:

            sys.exit(32)

    except (TypeError, IndexError, AttributeError):

        sys.exit(55)


# skopiruje hodnotu sym do premmennej
def move(var, sym):

    global TF, LF, GF

    sourceType, sourceValue = getTypeAndValue(sym)

    updateVar(var, sourceType, sourceValue)    

# skontroluje existenciu ramca a premennej v nom
def checkFrameAndVarSpace(var, frame):

    if (frame is None):

        sys.exit(55)

    if (var in frame):

        sys.exit(52)

    return


#vytvori premennu v dannom ramci
def defvar(frame, var_name):

    global LF, TF, GF

    try:

        if (frame == "LF"):

            checkFrameAndVarSpace(var_name, LF) 

            LF[-1].append([var_name, None, None]) 


        elif (frame == "TF"):
      
            checkFrameAndVarSpace(var_name, TF)   

            TF.append([var_name, None, None])
             

        elif (frame == "GF"):

            checkFrameAndVarSpace(var_name, GF)   

            GF[var_name] = [var_name, None, None]

        else:

            sys.exit(55)

    except (TypeError, IndexError, AttributeError):

        sys.exit(55)


# Presunie docasny ramec do (ak nejaky existuje) lokalneho ramcu 
def pushFrame():

    global TF, LF

    if (TF is None):

        sys.exit(55)

    if (LF is None):

        LF = []

    LF.append(TF)

    TF = None


# Presunie vrcholovy ramec zo zasobniku ramcov do docasneho ramcu
def popFrame():

    global LF, TF

    if (LF is None):

        sys.exit(55)

    if (TF is None):

        TF = []

    
    try: TF = LF[-1]

    except IndexError: sys.exit(55)

    del LF[-1]

#ulozi hodnotu na dataovy zasobnik
def pushs(sym):

    type, value = getTypeAndValue(sym)

    dataStack.append([type, value])



# Vyberie hodnotu z datoveho zasobnika (pokial nieje prazdny) a vlozi ju do danej premmenej
def pops(var):

    if not dataStack:

        sys.exit(56)

    

    updateVar(var, dataStack[-1][0], dataStack[-1][1]) 

    del dataStack[-1]




#ziska typ a hodnotu symbolov pre aritmetiku
def setupsymols(s1, s2):

    s1t,s1v = getTypeAndValue(s1) 

    s2t,s2v = getTypeAndValue(s2)

    return s1t, s1v, s2t, s2v



# porovna s1 a s2 z typom 
def arithmeticTypeCheck(s1, s2, type):

    s1_type, s1_value, s2_type, s2_value = setupsymols(s1, s2)

    if (s1_type != s2_type or s1_type != type):

        sys.exit(53)



    return s1_value, s2_value


# ---------------------------Aritmetika---------------------------------------
#var - result 
#sym1 a sym2 su operandy na ktorych sa ma uskutocnit operacia
def add(var, sym1, sym2):

    s1_value, s2_value = arithmeticTypeCheck(sym1, sym2, "int")

    updateVar(var, "int", int(s1_value) + int(s2_value))



def sub(var, sym1, sym2):

    s1_value, s2_value = arithmeticTypeCheck(sym1, sym2, "int")

    updateVar(var, "int", int(s1_value) - int(s2_value))   



def mul(var, sym1, sym2):

    s1_value, s2_value = arithmeticTypeCheck(sym1, sym2, "int")

    updateVar(var, "int", int(s1_value) * int(s2_value))  



def idiv(var, sym1, sym2):

    s1_value, s2_value = arithmeticTypeCheck(sym1, sym2, "int")

    if int(s2_value) == 0:

        sys.exit(57)

    updateVar(var, "int", int(s1_value) / int(s2_value)) 



def strToBool(s):

    if s == "true":

        return True

    elif s == "false":

        return False




#relacne operacie
def compare(s1, s2, operator):

    value = "false"

    if (operator == "LT"):

        if s1 < s2:

            value = "true"

    elif (operator == "GT"):

        if s1 > s2:

            value = "true"

    elif (operator == "EQ"):

        if s1 == s2:

            value = "true"



    return value



#spracovanie relacnej instrukcie tato funkcia na samotne porovnanie vola funkciu compare
def ltgteq(var, sym1, sym2, operator):
    value = "false"
    s1_type, s1_value = getTypeAndValue(sym1)
    s2_type, s2_value = getTypeAndValue(sym2)
    if (s1_type != "nil" and s2_type != "nil" and s1_type != s2_type):

        sys.exit(53)

    else:

        value = ""

        if s1_type == "nil" or s2_type == "nil":

            if (operator != "EQ"):

                sys.exit(53)

            if s1 == s2:

                value = "true"

        elif s1_type == "int":

            try:

                s1_value = int(s1_value)

                s2_value = int(s2_value)

                value = compare(s1_value, s2_value, operator)

            except: sys.exit(32)

        elif s1_type == "bool":

            s1_value = strToBool(s1_value)

            s2_value = strToBool(s2_value)   

            value = compare(s1_value, s2_value, operator)



    updateVar(var, "bool", value)


# funkcia prevedie integer(sym) na char a ulozi do var
def int2char(var, sym):

    type, value = getTypeAndValue(sym)

    if type != "int":

        sys.exit(53)

    value = int(value)

    try: result = chr(value)

    except: sys.exit(58)



    updateVar(var, "int", result)


# funkcia prevedie n-ty char v stringu(sym) (kde n je sym2) na int a ulozi do var
def stri2int(var, sym1, sym2):

    s1_type, s1_value = getTypeAndValue(sym1)

    s2_type, s2_value = getTypeAndValue(sym2)

    if s1_type != "string" or s2_type != "int":

        sys.exit(53)

    try:

        result = s1_value[int(s2_value)]

        result = ord(result)

    except: sys.exit(58)

    updateVar(var, "int", result)


# do var nacita hodnotu zo STDIN typu (type)
def read(var, type):

    global inCode, inContent

    type, value = getTypeAndValue(type)

    if inCode is None:

        try: line = input()

        except: line=""

    else:

        if (inContent is None):

            inContent = open(inCode, 'r')

        

        line = inContent.readline().rstrip() 

            

    if value == "string":

        updateVar(var, "string", line)

    elif value == "bool":

        if line.lower() == "true":

            updateVar(var, "bool", "true")

        else:

            updateVar(var, "bool", "false")

    elif value == "int":

        try: updateVar(var, "int", str(int(line)))

        except: updateVar(var, "int", "0")


# Vypise hodnotu symbolu pmocou prikazu print()
def write(sym):

    type, value = getTypeAndValue(sym)

    if type == "nil":

        value = ""

    print(value)



# spoji dokopy 2 stringy (sym1 a sym2) a ulozi vysledok do var
def concat(var, sym1, sym2):

    s1_type, s1_value = getTypeAndValue(sym1)

    s2_type, s2_value = getTypeAndValue(sym2)

    if (s1_type != "string" or s2_type != "string"):

        sys.exit(53)

    try: result = s1_value + s2_value

    except: sys.exit(58)

    updateVar(var, "string", result)



# do var ulozi dlzku danneho symbolu
def strlen(var, sym):

    type, value = getTypeAndValue(sym)

    if (type != "string"):

        sys.exit(53)

    updateVar(var, "int", len(value))

# do var ulozi char na n-tej pozicii(kde n je sym2) zo symbolu1
def getchar(var, sym1, sym2):

    s1_type, s1_value = getTypeAndValue(sym1)

    s2_type, s2_value = getTypeAndValue(sym2)

    if (s1_type != "string" or s2_type != "int"):

        sys.exit(53)

    try: value = s1_value[int(s2_value)]

    except: sys.exit(58)

    updateVar(var, "string", value)


# vo var nahradi char na n-tej pozicii(kde n je sym2) za symbol1
def setchar(var, sym1, sym2):

    var_type, var_value = getTypeAndValue(var)

    s1_type, s1_value = getTypeAndValue(sym1)

    s2_type, s2_value = getTypeAndValue(sym2)

    if (var_type != "string" or s1_type != "int" or s2_type != "string"):

        sys.exit(53)

    try:

        value = list(var_value)

        value[int(s1_value)] = s2_value[0]

        value = ''.join(value)

    except: sys.exit(58)    

    updateVar(var, "string", value)

# do var ulozi typ symbolu (sym)
def itype(var, sym):

    type, value = getTypeAndValue(sym, True)

    if type == None or value == None:

        type = ""

    updateVar(var, "string", type)

# ukonci program s navratovym kodom v symbole (sym)
def iexit(sym):

    type, value = getTypeAndValue(sym)

    if type != "int":

        sys.exit(53)
    if int(value) < 0 or int(value) > 49:

        sys.exit(57)



    sys.exit(int(value))




# vypise sym na stderr
def dprint(sym):

    type, value = getTypeAndValue(sym, True)

    sys.stderr.write(value)

  
#do var ulozi vysledok operacie sym1 AND/OR sym2 podla (operator)
def AND_and_OR(var, sym1, sym2, operator):

    s1_type, s1_value = getTypeAndValue(sym1)
    s2_type, s2_value = getTypeAndValue(sym2)

    if (s1_type != "bool" or s2_type != "bool"):

        sys.exit(53)

    value ="false"

    if operator == "AND":

        if s1_value == "true" and s2_value == "true":

            value = "true"

    elif operator == "OR":

        if s1_value == "true" or s2_value == "true":

            value = "true"



    updateVar(var, "bool", value)

#do var ulozi znegovany boolovsku hodnotu zo symbolu (sym)
def inot(var, sym):

    type, value = getTypeAndValue(sym)

    if type != "bool":

        sys.exit(53)

    if value == "false":

        updateVar(var, "bool", "true") 

    else:

        updateVar(var, "bool", "false") 


# funkcia na samotne interpretovanie ktora podla nazvu instrukcie zavola prislusnu funkciu

def interpret(code):
    global tFrame, lFrame, gFrame

    for instr in code:

        i = 1

        while i != len(instr):

            instr[i]=instr[i].split("@")

            i+=1

        if (instr[0] == "MOVE"):

            sourceType, sourceValue = getTypeAndValue(instr[2])

            updateVar(instr[1], sourceType, sourceValue) 


        elif (instr[0] == "DEFVAR"):

            defvar(instr[1][0], instr[1][1])


  
        elif (instr[0] == "CREATEFRAME"): 

             if (tFrame is None):
                 tFrame = []
                 tFrame.clear()
        

        elif (instr[0] == "PUSHFRAME"):  

            pushFrame()



        elif (instr[0] == "POPFRAME"): 

            popFrame()


        elif (instr[0] == "PUSHS"):

            pushs(instr[1])



        elif (instr[0] == "POPS"):

            pops(instr[1])


        
        elif (instr[0] == "EXIT"):

            iexit(instr[1])



        elif (instr[0] == "DPRINT"):

            dprint(instr[1])


        elif (instr[0] == "READ"):

            read(instr[1], instr[2])



        elif (instr[0] == "WRITE"):

            write(instr[1])



        elif (instr[0] == "ADD"):

            add(instr[1], instr[2], instr[3])



        elif (instr[0] == "SUB"):

            sub(instr[1], instr[2], instr[3])



        elif (instr[0] == "MUL"):

            mul(instr[1], instr[2], instr[3])



        elif (instr[0] == "IDIV"):

            idiv(instr[1], instr[2], instr[3])



        elif (instr[0] == "LT"):

            ltgteq(instr[1], instr[2], instr[3], "LT")



        elif (instr[0] == "GT"):

            ltgteq(instr[1], instr[2], instr[3], "GT")



        elif (instr[0] == "EQ"):

            ltgteq(instr[1], instr[2], instr[3], "EQ")



        elif (instr[0] == "AND"):

            AND_and_OR(instr[1], instr[2], instr[3], "AND")



        elif (instr[0] == "OR"):

            AND_and_OR(instr[1], instr[2], instr[3], "OR")



        elif (instr[0] == "NOT"):

            inot(instr[1], instr[2])



        elif (instr[0] == "INT2CHAR"):

            int2char(instr[1], instr[2])



        elif (instr[0] == "STRI2INT"):

            stri2int(instr[1], instr[2], instr[3])


        elif (instr[0] == "CONCAT"):

            concat(instr[1], instr[2], instr[3])



        elif (instr[0] == "STRLEN"):

            strlen(instr[1], instr[2])



        elif (instr[0] == "GETCHAR"):

            getchar(instr[1], instr[2], instr[3])



        elif (instr[0] == "SETCHAR"):

            setchar(instr[1], instr[2], instr[3])



        elif(instr[0] == "TYPE"):

            itype(instr[1], instr[2])


        elif (instr[0] == "CALL"):

            continue



        elif (instr[0] == "RETURN"):

            continue


        elif (instr[0] == "LABEL"):

            continue



        elif (instr[0] == "JUMP"):

            continue



        elif (instr[0] == "JUMPIFEQ"):

            continue



        elif (instr[0] == "JUMPIFNEQ"):

            continue


        elif (instr[0] == "BREAK"):

            continue



        else:

            sys.exit(32)


#   funkcia main ktora nacita potrebne subory pripadne vyhodi erro kody pokial sa nepodari ich nacitat
def main():

    global inCode

    if ("--help" in sys.argv):

        if (len(sys.argv) != 2):

            exit(10)

        printHelpAndExit()    



    if (len(sys.argv) == 1):

        exit(10)

    else:

        args = parseArgs()

        if ('source' in args):      #nacitanie zdrojoveho suboru, chyb. kod 11 ak sa nepodari
            try:
                sourceCode = open(args["source"],'r').read()

            except:

                sys.exit(11) 

            

        if ('input' in args):

            inCode = args["input"]

        if ('source' not in args):
            code = ""
            for line in sys.stdin:
                sourceCode += line 
        #volanie funkcii      
        lexxed = lex(sourceCode)
        syntaxed = syntax(lexxed)
        interpret(syntaxed)
        exit(0)

main()