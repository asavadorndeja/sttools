#!/usr/bin/python;
import sys
import ast
import json
from math import sqrt
import numpy as np
fFormat = "{:.{}f}"
#This program performs Wall Thickness Design Based on DNVGL-ST-F101

#Data
Es = 207e9
v = 0.3
Gamma_inc = 1.1
Gamma_m = 1.15
Gamma_SC_PC = [1.046, 1.138, 1.308]
Gamma_SC_LB = [1.04, 1.14, 1.26]
# Fig5-2
Fig5_2 = [[0,0,30,50,70],[0,40,90,120,140],[20,50,100,150,200]]
#Fig5_2 = [[0,0,20],[0,40,50],[30,90,100],[50,120,150],[70,140,200]]
Fig5_3 = [[1.00,0.96],[1.00,1.00]]
Tab5_4 = [1.00, 0.93, 0.85]



#Global Functions
def SecantSolve(x0,dx,err,f):
    x = x0
    x0 = x0+dx
    while (abs(f(x))>err):
        xt0 = x
        x = x-f(x)*(x-x0)/(f(x)-f(x0))
        x0 = xt0
    return x


#class declaration
class TPipeWT:
    count = 0
    def __init__(self,OD,Dmax,Dmin,SMYS, SMTS, tcor,tfab,Topt=25,FAB=0,MAT=0,SUP=0):
        self.OD = OD #Outer diameter, m
        self.Dmax = Dmax #Max outer diameter, m
        self.Dmin = Dmin #Min outer diameter,m
        self.SMYS = SMYS #MPa
        self.SMTS = SMTS #MPa
        self.tcor = tcor #Corrosion allowance thickness, m
        self.tfab = tfab #Fabrication tolerance thickness, m
        self.Topt = Topt #Operating temperature, deg
        self.FAB = FAB # [1,2,3] where: (1)-Seamless (2)-OU,TRB,ERB,HWF (3)-UOE
        self.MAT = MAT # [1,2] where: (1)-Cmn & 13Cr (2)-22Cr & 25Cr
        self.SUP = SUP # [1,2] where: (1)-Normal  (2) with supplementary requirement-U

        self.a_u = Fig5_3[SUP]
        self.a_fab = Tab5_4[FAB]
        self.fy_temp = np.interp(Topt,Fig5_2[2],Fig5_2[MAT])
        self.fu_temp = self.fy_temp
        self.fy = (self.SMYS-self.fy_temp)*self.a_u[1] #Mpa
        self.fu = (self.SMTS-self.fu_temp)*self.a_u[1] #MPa
        self.fy_test = (self.SMYS-self.fy_temp)*self.a_u[0] #Mpa
        self.fu_test = (self.SMTS-self.fu_temp)*self.a_u[0] #Mpa
        self.fcb = min(self.fy,self.fu/1.15)
        self.fcb_test = min(self.fy_test,self.fu_test/1.15)
        self.Gamma_Hyd_PC1=Gamma_SC_PC[0]
        self.Gamma_Opt_PC1=Gamma_SC_PC[1]
        self.Gamma_Opt_LB1=Gamma_SC_LB[1]
        self.Gamma_Hyd_PC2=Gamma_SC_PC[0]
        self.Gamma_Opt_PC2=Gamma_SC_PC[2]
        self.Gamma_Opt_LB2=Gamma_SC_LB[2]
        self.Oval = (Dmax-Dmin)/OD


    def ShowData(self):
        print('------------INPUT-------------')
        print('Class Name: ',self.__class__.__name__)
        print('Outside Diameter: {0:.3f} m'.format(self.OD ))
        print('Dmax: {0:.3f} m'.format(self.Dmax))
        print('Dmin: {0:.3f} m'.format(self.Dmin) )
        print('SMYS: {0:.3f} MPa'.format(self.SMYS) )
        print('SMTS: {0:.3f} MPa'.format(self.SMTS) )
        print('tcor: {0:.3f} m'.format(self.tcor) )
        print('tfab: {0:.3f} m'.format(self.tfab) )
        print('FAB: {0:.0f} '.format(self.FAB) )
        print('MAT: {0:.0f} '.format(self.MAT) )
        print('SUP: {0:.0f} '.format(self.SUP) )
        print('a_u[0]: {0:.3f} '.format(self.a_u[0]) )
        print('a_u[1]: {0:.3f} '.format(self.a_u[1]) )
        print('a_fab: {0:.3f} '.format(self.a_fab) )
        print('fy_temp: {0:.3f} '.format(self.fy_temp) )
        print('fu_temp: {0:.3f} '.format(self.fu_temp) )
        print('fy: {0:.3f} '.format(self.fy) )
        print('fu: {0:.3f} '.format(self.fu) )
        print('fy_test: {0:.3f} '.format(self.fy_test) )
        print('fu_test: {0:.3f} '.format(self.fu_test) )
        print('fcb: {0:.3f} '.format(self.fcb) )
        print('fcb_test: {0:.3f} '.format(self.fcb_test) )
        print('Gamma_Hyd_PC1: {0:.3f} '.format(self.Gamma_Hyd_PC1) )
        print('Gamma_Opt_PC1: {0:.3f} '.format(self.Gamma_Opt_PC1) )
        print('Gamma_Opt_PC1: {0:.3f} '.format(self.Gamma_Opt_LB1) )
        print('Gamma_Hyd_PC2: {0:.3f} '.format(self.Gamma_Hyd_PC2) )
        print('Gamma_Opt_PC2: {0:.3f} '.format(self.Gamma_Opt_PC2) )
        print('Gamma_Opt_PC2: {0:.3f} '.format(self.Gamma_Opt_LB2) )
        print('Ovality: {0:.3f} %'.format(self.Oval*100) )

class TOperatingWT:
    count = 0
    def __init__(self,Cden,Tden,Pd,Pt, Pe, hl,href):
        self.Cden = Cden #Operating content density, kg/m3
        self.Tden = Tden #System test content density, kg/m3
        self.Pd = Pd #Design pressure,MPa
        self.Pt = Pt #System test pressure,MPa
        self.Pe = Pe #Design external pressure,MPa
        self.hl = hl #Elevation of local pressure point (positive upward),m
        self.href = href #Elevation of reference point (positive upward),m

        self.Pinc = Pd*Gamma_inc
        self.Pli = self.Pinc - Cden*9.81*(hl-href)/1000000
        self.Plt = Pt - Tden*9.81*(hl-href)/1000000

    def ShowData(self):
        print('------------INPUT-------------')
        print('Class Name: ',self.__class__.__name__)
        print('Cden: {0:.3f} kg/m3'.format(self.Cden ))
        print('Tden: {0:.3f} kg/m3'.format(self.Tden))
        print('Pd: {0:.3f} MPa'.format(self.Pd) )
        print('Pt: {0:.3f} MPa'.format(self.Pt) )
        print('Pe: {0:.3f} MPa'.format(self.Pe) )
        print('hl: {0:.3f} m'.format(self.hl) )
        print('href: {0:.3f} m'.format(self.href) )
        print('Pinc: {0:.3f} MPa'.format(self.Pinc) )
        print('Pli: {0:.3f} MPa'.format(self.Pli) )
        print('Plt: {0:.3f} MPa'.format(self.Plt) )


class DNVSTF101WT:
    count = 0
    def __init__(self,PipeWT,OperatingWT):
        self.OD = PipeWT.OD #Operating content density, kg/m3
        self.tcor = PipeWT.tcor
        self.tfab = PipeWT.tfab
        #Data for bursting
        self.fcb = PipeWT.fcb #System test content density, kg/m3
        self.fcb_test = PipeWT.fcb_test
        self.Pli = OperatingWT.Pli
        self.Plt = OperatingWT.Plt
        self.Pe = OperatingWT.Pe
        self.Gamma_Opt_PC1 = PipeWT.Gamma_Opt_PC1
        self.Gamma_Opt_PC2 = PipeWT.Gamma_Opt_PC2
        self.Gamma_Hyd_PC1 = PipeWT.Gamma_Hyd_PC1
        self.Gamma_Hyd_PC2 = PipeWT.Gamma_Hyd_PC2
        self.tb_opt1 = self.Cal_t_burst(self.OD,self.fcb,self.Pli,self.Pe, self.Gamma_Opt_PC1,Gamma_m)
        self.tb_opt2 = self.Cal_t_burst(self.OD,self.fcb,self.Pli,self.Pe, self.Gamma_Opt_PC2,Gamma_m)
        self.tb_hyd1 = self.Cal_t_burst(self.OD,self.fcb_test,self.Plt,self.Pe, self.Gamma_Hyd_PC1,Gamma_m)
        self.tb_hyd2 = self.Cal_t_burst(self.OD,self.fcb_test,self.Plt,self.Pe, self.Gamma_Hyd_PC2,Gamma_m)
        self.tb_req_opt1 = self.tb_opt1 + self.tcor + self.tfab
        self.tb_req_opt2 = self.tb_opt2 + self.tcor + self.tfab
        self.tb_req_hyd1 = self.tb_hyd1 + self.tfab
        self.tb_req_hyd2 = self.tb_hyd2 + self.tfab
        #Data for collapse
        self.Oval = PipeWT.Oval
        self.fy = PipeWT.fy
        self.a_fab = PipeWT.a_fab
        self.Gamma_Opt_LB1 = PipeWT.Gamma_Opt_LB1
        self.Gamma_Opt_LB2 = PipeWT.Gamma_Opt_LB2
        self.Pc_req1 = self.Pe*self.Gamma_Opt_LB1*Gamma_m
        self.Pc_req2 = self.Pe*self.Gamma_Opt_LB2*Gamma_m
        self.tc_opt1 = self.Cal_t_collapse(self.OD,self.Pc_req1,self.Oval,self.fy,self.a_fab)
        self.tc_opt2 = self.Cal_t_collapse(self.OD,self.Pc_req2,self.Oval,self.fy,self.a_fab)
        self.tc_req_opt1 = self.tc_opt1 + self.tcor + self.tfab
        self.tc_req_opt2 = self.tc_opt2 + self.tcor + self.tfab
        #Data for cpropagation buckling
        self.Pp_req1 = self.Pe*self.Gamma_Opt_LB1*Gamma_m
        self.Pp_req2 = self.Pe*self.Gamma_Opt_LB2*Gamma_m
        self.tp_opt1 = self.Cal_t_propagation(self.OD,self.Pp_req1,self.fy,self.a_fab)
        self.tp_opt2 = self.Cal_t_propagation(self.OD,self.Pp_req2,self.fy,self.a_fab)
        self.tp_req_opt1 = self.tp_opt1 + self.tcor
        self.tp_req_opt2 = self.tp_opt2 + self.tcor



    def Cal_t_burst(self,D,fcb,Pi,Pe,Gamma_SC,Gamma_m):
        Pb = lambda t:(2*t)/(D-t)*fcb*2/sqrt(3)
        func = lambda t:Gamma_SC*Gamma_m*(Pi-Pe)- Pb(t)
        t0 = 0.001
        tb = SecantSolve(t0,0.001,10e-5,func)

        return tb

    def Cal_t_collapse(self,D,Pcr,Oval,fy,a_fab):
        Pel = lambda t:2*Es*(t/D)**3/(1-v**2)/1000000
        Pp = lambda t:fy*a_fab*2*t/D
        func = lambda t:(Pcr-Pel(t))*(Pcr**2-Pp(t)**2)-(Pcr*Pel(t)*Pp(t)*Oval*D/t)
        t0 = 100
        tc = SecantSolve(t0,0.001,10e-5,func)

        return tc #Pp(0.001)

    def Cal_t_propagation(self,D,Pp,fy,a_fab):
        Ppr = lambda t:35*fy*a_fab*(t/D)**2.5
        func = lambda t:Ppr(t)-Pp
        t0 = 0.1
        tp = SecantSolve(t0,0.001,10e-5,func)

        return tp #Pp(0.001)



    def ShowData(self):
        print('------------INPUT-------------')
        print('Class Name: ',self.__class__.__name__)
        print('tcor: {0:.3f} m'.format(self.tcor ))
        print('tfab: {0:.3f} m'.format(self.tfab ))
        print('tPc_req1: {0:.3f} MPa'.format(self.Pc_req1 ))
        print('......')
        print('Result: Busting - Operating Condition')
        print('Characteristic thickness(Zone 1), tb_opt1: {0:.3f} mm'.format(self.tb_opt1*1000 ))
        print('Characteristic thickness(Zone 2), tb_opt2: {0:.3f} mm'.format(self.tb_opt2*1000 ))
        print('Min required thickness(Zone 1), tb_req_opt1: {0:.3f} mm'.format(self.tb_req_opt1*1000 ))
        print('Min required thickness(Zone 2), tb_opt2: {0:.3f} mm'.format(self.tb_req_opt2*1000 ))
        print('......')
        print('Result: Busting - System Test Condition')
        print('Characteristic thickness(Zone 1), tb_hyd1: {0:.3f} mm'.format(self.tb_hyd1*1000 ))
        print('Characteristic thickness(Zone 2), tb_hyd2: {0:.3f} mm'.format(self.tb_hyd2*1000 ))
        print('Min required thickness(Zone 1), tb_hyd1: {0:.3f} mm'.format(self.tb_req_hyd1*1000 ))
        print('Min required thickness(Zone 2), tb_hyd2: {0:.3f} mm'.format(self.tb_req_hyd2*1000 ))
        print('......')
        print('Result: Collapse - Operating Condition')
        print('Characteristic thickness(Zone 1), tc_opt1: {0:.3f} mm'.format(self.tc_opt1*1000 ))
        print('Characteristic thickness(Zone 2), tc_opt2: {0:.3f} mm'.format(self.tc_opt2*1000 ))
        print('Min required thickness(Zone 1), tc_opt1: {0:.3f} mm'.format(self.tc_req_opt1*1000 ))
        print('Min required thickness(Zone 2), tc_opt2: {0:.3f} mm'.format(self.tc_req_opt2*1000 ))
        print('......')
        print('Result: Propagation Buckling - Operating Condition')
        print('Characteristic thickness(Zone 1), tp_opt1: {0:.3f} mm'.format(self.tp_opt1*1000 ))
        print('Characteristic thickness(Zone 2), tp_opt2: {0:.3f} mm'.format(self.tp_opt2*1000 ))
        print('Min required thickness(Zone 1), tp_opt1: {0:.3f} mm'.format(self.tp_req_opt1*1000 ))
        print('Min required thickness(Zone 2), tp_opt2: {0:.3f} mm'.format(self.tp_req_opt2*1000 ))
        print('......')

    def jSon(self):
        input = {
            'Class Name': self.__class__.__name__
        }

        output_zone1 = {
            'Bursting-Operating: Required characteristic thickness, t1, mm' : fFormat.format(self.tb_opt1*1000, 2),
            'Bursting-System Test: Required characteristic thickness, t1, mm' : fFormat.format(self.tb_hyd1*1000, 2),
            'Collapse-Operating: Required characteristic thickness, t1, mm' : fFormat.format(self.tc_opt1*1000, 2),
            'Propagation Buckling-Operating: Required characteristic thickness, t2, mm' : fFormat.format(self.tp_opt1*1000, 2),
            'Total required characteristic thickness, ttot , mm' : fFormat.format(max(self.tb_opt1,self.tb_hyd1,self.tc_opt1,self.tp_opt1)*1000, 2),
            'Bursting-Operating: Required minimum thickness, treq, mm' : fFormat.format(self.tb_req_opt1*1000, 2),
            'Bursting-System Test: Required minimum thickness, treq, mm' : fFormat.format(self.tb_req_hyd1*1000, 2),
            'Collapse-Operating: Required minimum thickness, treq, mm' : fFormat.format(self.tc_req_opt1*1000, 2),
            'Propagation Buckling-Operating: Required minimum thickness, treq, mm' : fFormat.format(self.tp_req_opt1*1000, 2),
            'Total required minimum thickness, treq_tot , mm' : fFormat.format(max(self.tb_req_opt1,self.tb_req_hyd1,self.tc_req_opt1,self.tp_req_opt1)*1000, 2),

        }

        output_zone2 = {
            'Bursting-Operating: Required characteristic thickness, t1, mm' : fFormat.format(self.tb_opt2*1000, 2),
            'Bursting-System Test: Required characteristic thickness, t1, mm' : fFormat.format(self.tb_hyd2*1000, 2),
            'Collapse-Operating: Required characteristic thickness, t1, mm' : fFormat.format(self.tc_opt2*1000, 2),
            'Propagation Buckling-Operating: Required characteristic thickness, t2, mm' : fFormat.format(self.tp_opt2*1000, 2),
            'Total required characteristic thickness, ttot , mm' : fFormat.format(max(self.tb_opt2,self.tb_hyd2,self.tc_opt2,self.tp_opt2)*1000, 2),
            'Bursting-Operating: Required minimum thickness, treq, mm' : fFormat.format(self.tb_req_opt2*1000, 2),
            'Bursting-System Test: Required minimum thickness, treq, mm' : fFormat.format(self.tb_req_hyd2*1000, 2),
            'Collapse-Operating: Required minimum thickness, treq, mm' : fFormat.format(self.tc_req_opt2*1000, 2),
            'Propagation Buckling-Operating: Required minimum thickness, treq, mm' : fFormat.format(self.tp_req_opt2*1000, 2),
            'Total required minimum thickness, treq_tot , mm' : fFormat.format(max(self.tb_req_opt2,self.tb_req_hyd2,self.tc_req_opt2,self.tp_req_opt2)*1000, 2),

        }



        result = [output_zone1, output_zone2]

        return result

#**********************************************
# MAIN
#**********************************************
#Default Input

# Input for TPipeWT
plOD = 0.27305 #m
plDmax=0.275 #m
plDmin=0.270 #m
plSMYS=358 #MPa
plSMTS=455 #MPa
pltcor=0.003 #m
pltfab=0.001 #m
plTopt=60 #DegC
plFAB=0
plMAT=0
plSUP=0

# Input for TOperatingWT
opCden=200 #Content density, kg/m3
opTden=1025 #System test fluid density, kg/m3
opPd=10 #MPa
opPt=11 #MPa
opPe=1 #MPa
ophl=10 #m
ophref=100 #m

#********************************************

#Input from Web
#print('len(argv) = ',len(sys.argv))

if len(sys.argv) > 1:
    #for i in range(1,19):
        #print(sys.argv[i])
        #print('sys.argv' +  str(1))

    #print(sys.argv)
    #print('len(sys.argv) = ',len(sys.argv))
    plOD = float(sys.argv[1])
    plDmax = float(sys.argv[2])
    plDmin = float(sys.argv[3])
    plSMYS = float(sys.argv[7])
    plSMTS = float(sys.argv[8])
    pltcor = float(sys.argv[10])
    pltfab = float(sys.argv[9])
    plTopt = float(sys.argv[15])
    plFAB = int(sys.argv[4])
    plMAT = int(sys.argv[5])
    plSUP = int(sys.argv[6])
    opCden = float(sys.argv[13])
    opTden = float(sys.argv[11])
    opPd = float(sys.argv[14])
    opPt = float(sys.argv[12])
    opPe = float(sys.argv[16])
    ophl = float(sys.argv[17])
    ophref = float(sys.argv[18])



#Calculation
Pipe = TPipeWT(
    OD = plOD,
    Dmax = plDmax,
    Dmin = plDmin,
    SMYS = plSMYS,
    SMTS = plSMTS,
    tcor = pltcor,
    tfab = pltfab,
    Topt = plTopt,
    FAB = plFAB,
    MAT = plMAT,
    SUP = plSUP)
Operating = TOperatingWT(
    Cden = opCden,
    Tden = opTden,
    Pd = opPd,
    Pt = opPt,
    Pe = opPe,
    hl = ophl,
    href = ophref)



#print('OD = ',plOD)
Result = DNVSTF101WT(Pipe,Operating)

#********************************************
#Show Results
#********************************************
'''
Pipe.ShowData()
Operating.ShowData()
Result.ShowData()
'''
print(json.dumps(Result.jSon()))
