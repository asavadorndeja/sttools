#!/usr/bin/python;
import sys
import ast
import json

import math as m
import numpy as np
# from scipy.interpolate import interp1d
# from scipy.optimize import fsolve

# Version Controller
sTitle = 'DNVGL RP F103 Cathodic protection of submarine pipelines'
sVersion = 'Version 1.0.0'

# Define constants
pi = m.pi
e = m.e

precision = 2
fFormat = "{:.{}f}"
separate = "--------------------"

Table62 = np.array([
    [25, 0.050, 0.020],
    [50, 0.060, 0.030],
    [80, 0.075, 0.040],
    [120, 0.100, 0.060],
    [200, 0.130, 0.080],

])

Table63 = np.array([
    ['Al-Zn-In', 30, -1.050, 2000, -1.000, 1500],
    ['Al-Zn-In', 60, -1.050, 1500, -1.000,  680],
    ['Al-Zn-In', 80, -1.000,  720, -1.000,  320],
    ['Zn', 30, -1.030,  780, -0.980,  750],
    ['Zn', 50, -1.050, 2000, -0.750,  580],

])

TableA1 = np.array([
    ['Glass fibre reincored alphat enamel', True, 70, 0.01, 0.0003],
    ['FBE', True, 90, 0.030, 0.0003],
    ['FBE', False, 90, 0.030, 0.0010],
    ['3-layer FBE/PE', True, 80, 0.001, 0.00003],
    ['3-layer FBE/PE', False, 80, 0.001, 0.00003],
    ['3-layer FBE/PP', True, 110, 0.001, 0.00003],
    ['3-layer FBE/PP', False, 80, 0.001, 0.00003],
    ['FBE/PP Thermally insulating coating', False, 140, 0.0003, 0.00001],
    ['FBE/PU Thermally insulating coating', False, 70, 0.01, 0.003],
    ['Polychloroprene', False, 90, 0.01, 0.001],
])

TableA2 = np.array([
    ['none', '4E(1) moulded PU on top bare steel (with primer)', 70, 0.30, 0.030],
    ['1D Adhesive Tape or 2A(1)/2A-(2) HSS (PE/PP backing) with mastic adhesive', '4E(2) moulded PU on top 1D or 2A(1)/2A(2)', 70, 0.10, 0.010],
    ['2B(1) HSS (backing + adhesive in PE with LE primer)', 'none', 70, 0.03, 0.003],
    ['2B(1) HSS (backing + adhesive in PE with LE primer)', '4E(2) moulded PU on 0.03 0.003 top 2B(1)', 70, 0.03, 0.003],
    ['2C (1) HSS (backing + adhesive in PP, LE primer)', 'none', 110, 0.03, 0.003],
    ['2C (1) HSS (backing + adhesive in PP, LE primer)', '4E(2) moulded PU on top 2B(1)', 110, 0.03, 0.003],
    ['3A FBE', 'none', 90, 0.10, 0.010],
    ['3A FBE', '4E(2) moulded PU on top', 90, 0.03, 0.003],
    ['2B(2) FBE with PE HSS', 'none', 70, 0.01, 0.0003],
    ['2B(2) FBE with PE HSS', '4E(2) moulded PU on top FBE + PE HSS', 70, 0.01, 0.0003],
    ['5D(1) and 5E FBE with PE applied as flame spraying or tape, respectively', 'none', 70, 0.01, 0.0003],
    ['2C(2) FBE with PP HSS', 'none', 140, 0.01, 0.0003],
    ['5A/B/C(1) FBE, PP adhesive and PP (wrapped, flame sprayed or moulded)', 'none', 140, 0.01, 0.0003],
    ['NA', '5C(1) Moulded PE on top FBE with PE adhesive', 70, 0.01, 0.0003],
    ['NA', '5C(2) Moulded PP on top FBE with PP adhesive', 140, 0.01, 0.0003],
    ['8A polychloroprene', 'none', 90, 0.03, 0.001],

])


# This function final mean current demand, M, in accodance with Eq. 3 of [1]
def Icf(Ac, fcf, icm, k):
    Icf = Ac*fcf*icm*k
    return Icf;

def fcf(a, b, t):
    fcf = a + b*tf
    return fcf;

# This function return required anode mass, M, in accodance with Eq.5 of [1]
def M(Icm, tf, u, e):
    M = (Icm*tf*8760)/(u*e)
    return M;


def DNVGLRPF113(D, lPL, lFJ, tAmb, tAno, tf, rhoSea, aGap, aThk, aDen, aMat, coatLP, coatFJ, nJoints, burial, u=0.8):

    k = 1.0
    EA0 = -0.85

    # determine mean current demand from Table 6-2 of [1]
    icm = [x for x in Table62 if x[0] > tAmb][0][burial]
    # print(icm)

    if aMat == False:
        aMaterial = 'Al-Zn-In'
    else:
        aMaterial = 'Zn'

    # determine anode properties from Table 6-3 of [1]
    anode = [x for x in Table63 if (x[0] == aMaterial and float(x[1]) >= float(tAno)) ][0]
    if burial == 1:
        EC0 = float(anode[2])
        e = float(anode[3])
    else:
        EC0 = float(anode[4])
        e = float(anode[5])
    # print(anode)
    # print(EC0)
    # print(e)

    # determine coating breakdown factor from Table A-1 of [1]
    coatingPL = TableA1[coatLP]
    aPL = float(coatingPL[3])
    bPL = float(coatingPL[4])
    # print(coatingPL)
    # print(aPL)
    # print(bPL)

    # determine field joint coating breakdown factor from Table A-2 of [1]
    coatingFJ = TableA2[coatFJ]
    aFJ = float(coatingFJ[3])
    bFJ = float(coatingFJ[4])
    # print(coatingFJ)
    # print(aFJ)
    # print(bFJ)

    # determine coating area
    Acl = pi*D*(lPL)*nJoints
    AclPL = pi*D*(lPL-2*lFJ)*nJoints
    AclFJ = pi*D*(2*lFJ)*nJoints
    # print(AclPL)
    # print(AclFJ)
    # print(Acl)


    #determine mean coating breakdown factor, Eq 2 of [1]
    fcmPL = aPL + 0.5*bPL*tf
    fcmFJ = aFJ + 0.5*bFJ*tf
    # print(fcmPL)
    # print(fcmFJ)


    #determine mean current demand, Eq 1 of [1]
    IcmPL = AclPL*fcmPL*icm*k
    IcmFJ = AclFJ*fcmFJ*icm*k
    Icm = IcmPL + IcmFJ
    # print(IcmPL)
    # print(IcmFJ)
    # print(Icm)


    #determine final coating breakdown factor, Eq 4 of [1]
    fcfPL = aPL + bPL*tf
    fcfFJ = aFJ + bFJ*tf
    # print(fcfPL)
    # print(fcfFJ)

    #determine final coating breakdown factor, Eq 3 of [1]
    IcfPL = AclPL*fcfPL*icm*k
    IcfFJ = AclFJ*fcfFJ*icm*k
    Icf = IcfPL + IcfFJ
    # print(IcfPL)
    # print(IcfFJ)
    # print(Icf)

    #determine minimun required anode mass, Eq. 5 of [1]
    reqM = (Icm*tf*8760)/(0.80*e)
    reqV = reqM/aDen
    # print('required anode mass',reqM)
    # print('required anode volume', reqV)

    unitV = (0.25*pi*((D + 2*aThk)**2) - 0.25*pi*(D**2) - 2*aGap*aThk)
    massLength = reqV/unitV
    # print('required anode length by mass', massLength)

    deltaE = EC0 - EA0

    reqA = (0.315*rhoSea*Icf/deltaE)**2
    unitA = pi*(D+2*(1-u)*aThk) - 2*aGap
    areaLength = reqA/unitA
    # print('required anode length by area', areaLength)

    input = [D, lPL, lFJ, tAmb, tAno, tf, rhoSea, aGap, aThk, aDen, aMat, coatLP, coatFJ, nJoints, burial]
    # output = [icm, anode, coatingPL, coatingFJ, reqM, reqV, massLength, areaLength]
    output = [icm, reqM, reqA, massLength, areaLength]
    report = []

    resultRaw = [input, output, report]

    inputJson = {
        'Outer diameter, m':D,
        'Length of pipeline, m':lPL,
        'Length of field joint':lFJ,
        'Ambient temperature, degC':tAmb,
        'Design life, year':tf,
        'Seawater density, kg/cu.m':rhoSea,
    }

    outPutJson = {
        'No of joints, N:':nJoints,
        'Mean current demand, A/Sq.m.': fFormat.format(icm, precision ),
        'Min. required anode mass, kg':fFormat.format(reqM, precision ),
        'Min. required surface area, Sq.m':fFormat.format(reqA, precision ),
        'Min. required length by anode mass, m':fFormat.format(massLength, precision ),
        'Min. required length by anode area, m':fFormat.format(areaLength, precision ),
    }

    resultJson = {'input':inputJson, 'output':outPutJson, 'report':report}

    result = [resultRaw, resultJson]
    return result;

D = 273.05E-03
lPL = 12
lFJ = 0.30
tAmb = 30
tAno = 30
tf = 30
rhoSea = 1
aGap = 25E-03
aThk = 50E-03
aDen = 2700
aMat = 0
coatLP = 0
coatFJ = 0
spaceMin = 10
spaceMax = 10
burial = 1          # 1 for non burial and 2 for burial


if __name__ == "__main__":
    D = float(sys.argv[1])
    lPL = float(sys.argv[2])
    lFJ = float(sys.argv[3])
    tAmb = float(sys.argv[4])
    tAno = float(sys.argv[5])
    tf = float(sys.argv[6])
    rhoSea = float(sys.argv[7])
    aGap = float(sys.argv[8])
    aThk = float(sys.argv[9])
    aDen = float(sys.argv[10])
    aMat = int(sys.argv[11])
    coatLP = int(sys.argv[12])
    coatFJ = int(sys.argv[13])
    spaceMin = int(sys.argv[14])
    spaceMax = int(sys.argv[15])
    burial = int(sys.argv[16])

resultJson = []

for nJoints in range(spaceMin, spaceMax + 1):
    result = DNVGLRPF113(D, lPL, lFJ, tAmb, tAno, tf, rhoSea, aGap, aThk, aDen, aMat, coatLP, coatFJ, nJoints, burial)
    resultJson.append(result[1])

print (json.dumps(resultJson))
