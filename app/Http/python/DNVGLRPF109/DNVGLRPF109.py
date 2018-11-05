#!/usr/bin/python;
import sys
import ast
import json
from math import pi,sqrt,log,sin,cos,gamma, degrees, radians
import numpy as np
#from scipy.special import gamma
import scipy.integrate as integrate

# Define analysis Constant
g = 9.81
fFormat = "{:.{}f}"


# This program performs On-Bottom Stability Analysis (CLAY) Based on DNVGL-RP-F109
# References
# [1] DNV, On-bottom stability design of subramine pipelines, DNVGL-RP-F109

def SandGrainSize(n):
    if n<=7:
        Ans = 2**(n-1)*0.0625
    elif (n>=9)and(n<12):
        Ans = 2**(n-9)*25
    elif (n>=12):
        Ans = 2**(n-12)*250
    else:
        Ans = 10
    return Ans

#SGZ = [SandGrainSize(i) for i in range(1,13+1)]
SGZ = [0.0625,0.25,0.5,1.0,4.0,25,125,500]
SRH = [5e-6,1e-5,4e-5,1e-4,3e-4,2e-3,1e-2,4e-2]

#function to interpolate Sand Roughness using GrainSize as input)
fGR = lambda x:np.interp(x,SGZ,SRH)

# This function return the array of Table 3-1
Table31 =[
    ['Silt and clay', 0.0625, 5E-06],
    ['Fine sand', 0.25, 1E-05],
    ['Medium sand', 0.5, 4E-05],
    ['Coarse sand', 1.0, 1E-04],
    ['Gravel', 4.0, 3E-04],
    ['Pebble', 25, 2E-03],
    ['Cobble', 125, 1E-02],
    ['Boulder', 500, 4E-02],
]

# Figure 3-2 of [1]
Fig32_TnTp = [0,0.01,0.02,0.03,0.04,0.05,0.06,0.07,0.08,0.09,0.1,0.11,0.12,0.13,0.14,0.15,0.16,0.17,0.18,
              0.19,0.2,0.21,0.22,0.23,0.24,0.25,0.26,0.27,0.28,0.29,0.3,0.31,0.32,0.33,0.34,0.35,0.36,
              0.37,0.38,0.39,0.4,0.41,0.42,0.43,0.44,0.45,0.46,0.47,0.48,0.49,0.5]
Fig32_G10 = [0.5,0.5,0.49,0.48,0.47,0.46,0.45,0.44,0.42,0.4,0.38,0.37,0.35,0.33,0.3,0.28,0.26,0.24,0.22,
             0.2,0.18,0.17,0.15,0.14,0.12,0.11,0.09,0.08,0.07,0.06,0.06,0.05,0.04,0.04,0.03,0.03,0.02,
             0.02,0.01,0.01,9.70E-03,8.23E-03,6.76E-03,5.30E-03,4.33E-03,3.61E-03,2.88E-03,2.16E-03,
             1.44E-03,7.21E-04,0]
Fig32_G33 = [0.5,0.5,0.49,0.49,0.48,0.46,0.45,0.44,0.43,0.41,0.4,0.38,0.36,0.34,0.32,0.3,0.28,0.26,0.24,
             0.22,0.21,0.19,0.17,0.15,0.13,0.12,0.1,0.09,0.08,0.07,0.06,0.05,0.05,0.04,0.03,0.03,0.02,
             0.02,0.02,0.01,0.01,9.78E-03,8.41E-03,7.07E-03,5.89E-03,4.72E-03,3.55E-03,2.58E-03,1.72E-03,
             8.61E-04,0]
Fig32_G50 = [0.5,0.5,0.49,0.48,0.48,0.47,0.45,0.44,0.43,0.42,0.4,0.38,0.37,0.35,0.33,0.31,0.29,0.27,0.25,
             0.23,0.21,0.2,0.18,0.16,0.14,0.12,0.11,0.1,0.08,0.07,0.06,0.06,0.05,0.04,0.04,0.03,0.02,
             0.02,0.02,0.02,0.01,0.01,9.24E-03,7.79E-03,6.33E-03,5.10E-03,4.08E-03,3.06E-03,2.04E-03,
             1.02E-03,0]
Fig32_fG10 = lambda x:np.interp(x,Fig32_TnTp,Fig32_G10)
Fig32_fG33 = lambda x:np.interp(x,Fig32_TnTp,Fig32_G33)
Fig32_fG50 = lambda x:np.interp(x,Fig32_TnTp,Fig32_G50)
Fig32_fG = lambda x,y:np.interp(y,[1.0,3.3,5.0],[Fig32_fG10(x),Fig32_fG33(x),Fig32_fG50(x)])

# Figure 3-3 of [1]
Fig33_TnTp = [0,0.01,0.02,0.03,0.04,0.05,0.06,0.07,0.08,0.09,0.1,0.11,0.12,0.13,0.14,0.15,0.16,0.17,0.18,
              0.19,0.2,0.21,0.22,0.23,0.24,0.25,0.26,0.27,0.28,0.29,0.3,0.31,0.32,0.33,0.34,0.35,0.36,
              0.37,0.38,0.39,0.4,0.41,0.42,0.43,0.44,0.45,0.46,0.47,0.48,0.49,0.5]
Fig33_G10 = [0.71,0.72,0.74,0.75,0.77,0.78,0.8,0.81,0.83,0.85,0.86,0.88,0.9,0.92,0.94,0.95,0.97,0.99,
             1.01,1.02,1.04,1.06,1.07,1.09,1.11,1.12,1.14,1.15,1.16,1.18,1.19,1.2,1.22,1.23,1.24,1.25,
             1.26,1.28,1.29,1.3,1.31,1.32,1.33,1.34,1.35,1.36,1.37,1.38,1.39,1.4,1.41]
Fig33_G33 = [0.77,0.79,0.8,0.81,0.82,0.84,0.85,0.86,0.88,0.89,0.9,0.92,0.93,0.94,0.95,0.96,0.98,0.99,1,
             1.01,1.02,1.03,1.04,1.06,1.07,1.08,1.09,1.1,1.12,1.13,1.14,1.16,1.17,1.18,1.2,1.21,1.23,
             1.24,1.25,1.27,1.28,1.3,1.31,1.32,1.34,1.35,1.36,1.37,1.38,1.4,1.41]
Fig33_G50 = [0.8,0.81,0.82,0.84,0.85,0.86,0.87,0.88,0.9,0.91,0.92,0.93,0.94,0.95,0.97,0.97,0.98,0.99,1,
             1.01,1.02,1.03,1.04,1.05,1.06,1.07,1.08,1.09,1.1,1.11,1.13,1.14,1.15,1.16,1.18,1.19,1.21,
             1.22,1.24,1.25,1.27,1.28,1.3,1.31,1.33,1.34,1.35,1.37,1.38,1.39,1.4]
Fig33_fG10 = lambda x:np.interp(x,Fig33_TnTp,Fig33_G10)
Fig33_fG33 = lambda x:np.interp(x,Fig33_TnTp,Fig33_G33)
Fig33_fG50 = lambda x:np.interp(x,Fig33_TnTp,Fig33_G50)
Fig33_fG = lambda x,y:np.interp(y,[1.0,3.3,5.0],[Fig33_fG10(x),Fig33_fG33(x),Fig33_fG50(x)])


# Eq 3.16 of [1]
fkt = lambda x:np.interp(x,[1.0,3.3,5.0],[1.25,1.21,1.17])

# Table 3-9 of [1]
KK = np.array([2.5, 5, 10, 20, 30, 40, 50, 60, 70, 100, 140])
MM = np.array([0,0.1,0.2,0.3,0.4,0.6,0.8,1,2,5,10])

CYY = np.array([[13,6.8,4.55,3.33,2.72,2.40,2.15,1.95,1.8,1.52,1.30],
               [10.7, 5.76, 3.72, 2.72, 2.20, 1.90, 1.71, 1.58, 1.49, 1.33, 1.22],
               [9.02, 5.00, 3.15 ,2.30, 1.85, 1.58, 1.42, 1.33, 1.27, 1.18, 1.14],
               [7.64, 4.32, 2.79, 2.01, 1.63, 1.44, 1.33, 1.26, 1.21, 1.14, 1.09],
               [6.63,3.8,2.51,1.78,1.46,1.32,1.25,1.19,1.16,1.1,1.05],
               [5.07,3.3,2.27,1.71,1.43,1.34,1.29,1.24,1.18,1.08,1],
               [4.01,2.7,2.01,1.57,1.44,1.37,1.31,1.24,1.17,1.05,1],
               [3.25,2.3,1.75,1.49,1.4,1.34,1.27,1.2,1.13,1.01,1],
               [1.52,1.5,1.45,1.39,1.34,1.2,1.08,1.03,1,1,1],
               [1.11,1.1,1.07,1.06,1.04,1.01,1,1,1,1,1],
               [1,1,1,1,1,1,1,1,1,1,1]])

fCYK0 = lambda k:np.interp(k,KK,CYY[0])
fCYK1 = lambda k:np.interp(k,KK,CYY[1])
fCYK2 = lambda k:np.interp(k,KK,CYY[2])
fCYK3 = lambda k:np.interp(k,KK,CYY[3])
fCYK4 = lambda k:np.interp(k,KK,CYY[4])
fCYK5 = lambda k:np.interp(k,KK,CYY[5])
fCYK6 = lambda k:np.interp(k,KK,CYY[6])
fCYK7 = lambda k:np.interp(k,KK,CYY[7])
fCYK8 = lambda k:np.interp(k,KK,CYY[8])
fCYK9 = lambda k:np.interp(k,KK,CYY[9])
fCYK10 = lambda k:np.interp(k,KK,CYY[10])
fCYMK1 = lambda m,k:np.interp(m,MM,[fCYK0(k),fCYK1(k),fCYK2(k),fCYK3(k),fCYK4(k),fCYK5(k),fCYK6(k),fCYK7(k),fCYK8(k),fCYK9(k),fCYK10(k)])
fCYMK = lambda m,k: fCYMK1(m,k) if k>=2.5 else fCYMK1(m,2.5)*2.5/k

# Table 3-10 of [1]
CZZ = np.array([[5,5,4.85,3.21,2.55,2.26,2.01,1.81,1.63,1.26,1.05],
                [3.87,4.08,4.23,2.87,2.15,1.77,1.55,1.41,1.31,1.11,0.97],
                [3.16,3.45,3.74,2.6,1.86,1.45,1.26,1.16,1.09,1,0.9],
                [3.01,3.25,3.53,2.14,1.52,1.26,1.1,1.01,0.99,0.95,0.9],
                [2.87,3.08,3.35,1.82,1.29,1.11,0.98,0.9,0.9,0.9,0.9],
                [2.21,2.36,2.59,1.59,1.2,1.03,0.92,0.9,0.9,0.9,0.9]  ,
                [1.53,1.61,1.8,1.18,1.05,0.97,0.92,0.9,0.9,0.9,0.9]  ,
                [1.05,1.13,1.28,1.12,0.99,0.91,0.9,0.9,0.9,0.9,0.9]  ,
                [0.96,1.03,1.05,1,0.9,0.9,0.9,0.9,0.9,0.9,0.9]  ,
                [0.91,0.92,0.93,0.91,0.9,0.9,0.9,0.9,0.9,0.9,0.9]  ,
                [0.9,0.9,0.9,0.9,0.9,0.9,0.9,0.9,0.9,0.9,0.9]])

fCZK0 = lambda k:np.interp(k,KK,CZZ[0])
fCZK1 = lambda k:np.interp(k,KK,CZZ[1])
fCZK2 = lambda k:np.interp(k,KK,CZZ[2])
fCZK3 = lambda k:np.interp(k,KK,CZZ[3])
fCZK4 = lambda k:np.interp(k,KK,CZZ[4])
fCZK5 = lambda k:np.interp(k,KK,CZZ[5])
fCZK6 = lambda k:np.interp(k,KK,CZZ[6])
fCZK7 = lambda k:np.interp(k,KK,CZZ[7])
fCZK8 = lambda k:np.interp(k,KK,CZZ[8])
fCZK9 = lambda k:np.interp(k,KK,CZZ[9])
fCZK10 = lambda k:np.interp(k,KK,CZZ[10])
fCZMK = lambda m,k:np.interp(m,MM,[fCZK0(k),fCZK1(k),fCZK2(k),fCZK3(k),fCZK4(k),fCZK5(k),fCZK6(k),fCZK7(k),fCZK8(k),fCZK9(k),fCZK10(k)])

#Global Functions

#Convert Degree to Radian
DtR = lambda x:x*pi/180

#class declaration


class TPipeInput:
    count = 0
    def __init__(self, OD, Ws, GammaSC, zp = 0, Pden = 7850, CL =0.9):
        self.OD = OD                            # Outer diameter of pipe, m
        self.Ws = Ws                            # Submerged weigth of pipe, N/m
        self.GammaSC = GammaSC                  # Safety class factor, -
        self.zp = zp                            # Pipe penetration in seabed
        self.Pden = Pden                        # Pipe density (kg/m3)
        self.CL = CL                            # Pipe lift coefficient, -, default 0.9

    def ShowData(self):
        print('------------INPUT-------------')
        print('Class Name: ',self.__class__.__name__)
        print('Overall Outside Diameter: {0:.3f} m'.format(self.OD ))
        print('Submerged Weight: {0:.3f} N/m'.format(self.Ws ))
        print('Safety Factor: {0:.3f} '.format(self.GammaSC) )
        print('Pipe penetration depth in seabed: {:.4E} m'.format(self.zp) )
        print('Pipe density: {0:.3f} kg/m3'.format(self.Pden) )

# Class for Environmental Data Section 3.4.3 and Section 3.4.4 of [1]
class TEnvForOBT:
    count = 0
    def __init__(self, WDepth, Hs, Tp, JSW, Ur, Zr, seaDen=1025, envDir=90, WaveDir=90, RD=0, ns=8):
        self.WDepth = WDepth                    # Water depth, m
        self.Hs = Hs                            # Significant Wave Height, m
        self.Tp = Tp                            # Wave peak period, s
        self.JSW = JSW                          # JONSWAP peakness parameter (gamma), -
        self.Ur = Ur                            # Current velocity at reference depth (Zr), m/s
        self.Zr = Zr                            # Reference depth (positive above from seabed), m
        self.seaDen = seaDen                    # Seawater density, kg/cu.m, default 1025 kg/cu.m.
        self.envDir = envDir                    # Environmental direction (Wave and current), deg
        self.WaveDir = WaveDir                  # Main Wave Direction, deg
        self.RD = RD                            # By default set RD = 0 to use Calculated Value
        self.ns = ns

        #Calculated values
        self.Tn = (self.WDepth/g)**0.5          # Period parameter, s Eq 3.14 of [1]
        self.TnTp = self.Tn/self.Tp             # Wave Period Ratio Tn/Tp
        self.C1 = Fig32_fG(self.TnTp,self.JSW)  # Factor from Fig 3-2 of [1]
        self.Us = self.C1*self.Hs/self.Tn       # Significant water velocity, m/s

        # self.Dw0 = self.WaveEngSpread(self.envDir)
        self.RDC_Intg = integrate.quad(self.waveEnergySpreading, radians(-90), radians(90))
        self.RDCal = sqrt(self.RDC_Intg[0])                 # Calculated reduction factor
        self.RDC = max(sqrt(self.RDC_Intg[0]),self.RD)      # Calculated RD, use this value in case RD = 0
        self.RDCErr = self.RDC_Intg[1]

        self.Uss = self.Us*self.RDC             # Significant flow velocity

        self.C2 = Fig33_fG(self.TnTp,JSW)       # Wave Period Ratio Tu/Tp, from Figure 3-3 of [1]
        self.Tu = self.C2*self.Tp               # Average zero-up crossing period, s
        self.kt = fkt(self.JSW)                 # Constant parameter kt
        self.kT = self.Find_kT()                # Wave period ratio kT
        self.Toc = self.kT*self.Tu
        self.tt = 10800/self.Tu              # Number of oscillation
        self.ku = 0.5*(sqrt(2*log(self.tt))+(0.5772)/sqrt(2*log(self.tt))) #Velocity Ratio Parameter ku
        self.Uoc = self.Uss*self.ku             # Design single oscillation velocity amplitude, m/s


    # This function return the wave spreading parameter in accodance with Section 3.4.4 of [1]
    def waveEnergySpreading(self, Ang):
        if abs(Ang) < radians(90):
            Dw = 1/sqrt(pi)*(gamma(1+self.ns/2))/(gamma(0.5+self.ns/2))*(cos(Ang)**self.ns)*sin(radians(self.envDir)-Ang)**2
        else:
            Dw = 0
        return Dw

    def Find_kT(self):
        if self.Tn/self.Tu <= 0.2:
            kT = self.kt-5*(self.kt-1)*self.Tn/self.Tu
        else:
            kT = 1
        return kT

    def ShowData(self):
        print(' ')
        print('------------INPUT-------------')
        print('Class Name: ',self.__class__.__name__)
        print('Water depth: {0:.3f} m'.format(self.WDepth) )
        print('Significant Wave Height: {0:.3f} m'.format(self.Hs) )
        print('Wave peak period: {0:.3f} sec'.format(self.Tp) )
        print('JONSWAP peakness parameter (gamma): {0:.3f} '.format(self.JSW) )
        print('Current velocity at reference depth (Zr): {0:.3f} m/s'.format(self.Ur) )
        print('Reference depth (positive above from seabed): {0:.3f} m'.format(self.Zr) )
        print('Main Wave Direction: {0:.3f} deg'.format(self.WaveDir) )
        print('RD input: {0:.3f} '.format(self.RD) )
        print('------------CALCULATION-------------')
        print('RD Calculated: {0:.3f} '.format(self.RDCal))
        print('RD Used: {0:.3f} '.format(self.RDC))
        print('Wave Period parameter, Tn: {0:.3f} sec'.format(self.Tn))
        print('Wave Period Ratio, Tn/Tp: {0:.3f} '.format(self.TnTp))
        print('Factor from Fig2.1, C1: {0:.3f} '.format(self.C1))
        print('Significant water velocity, Us: {0:.3f} m/s'.format(self.Us))
        print('Uss: {0:.3f} m/s'.format(self.Uss))
        print('Factor from Fig2.2, C2: {0:.3f} '.format(self.C2))
        print('Tu: {0:.3f}'.format(self.Tu))
        print('tt: {0:.3f}'.format(self.tt))
        print('ku: {0:.3f}'.format(self.ku))
        print('kt: {0:.3f}'.format(self.kt))
        print('kT: {0:.3f}'.format(self.kT))
        print('Toc: {0:.3f} sec'.format(self.Toc))
        print('Uoc: {0:.3f} m/s'.format(self.Uoc))
        print(' ')

    def jSon(self):

        input = {
            'Class name, -, -' : self.__class__.__name__,
            'Water depth, -, m' : fFormat.format(self.WDepth, 2),
            'Significant Wave Height, Hs, m' : fFormat.format(self.Hs, 2),
            'Peak wave period, Tp, sec' : fFormat.format(self.Tp, 2),
            'JONSWAP peakness parameter, -, m' : fFormat.format(self.JSW, 2),
            'Current velocity at reference depth, Ur, m/s' : fFormat.format(self.Ur, 2),
            'Reference depth (positive above from seabed), Zr, m' : fFormat.format(self.Zr, 2),
            'Seawater density, -, kg/Cu.m.' : fFormat.format(self.seaDen, 2),
            'Environmental direction, -, degree' : fFormat.format(self.envDir, 2),
            'Wave direction, -, m' : fFormat.format(self.WaveDir, 2),
            'Reduction factor due to spectral directionality and spreading RD, m' : fFormat.format(self.RD, 2),
        }

        output = {
            'Caculated reduction factor, -, -' : fFormat.format(self.RDCal, 2),
            'Design reduction factor, RD, -' : fFormat.format(self.RDC, 2),
            'Wave period parameter, Tn, Sec' : fFormat.format(self.Tn, 2),
            'Wave period ratio, Tn/Tp, -' : fFormat.format(self.TnTp, 2),
            'Factor from Figure 3-2, C1, -' : fFormat.format(self.C1, 2),
            'Significant water velocity, Us, -' : fFormat.format(self.Us, 2),
        }

        result = [input, output]
        return result

#Class for SoilDataSection 3.4.6 of [1]
class TSeabedOBT:
    count = 0
    def __init__(self, SoilType, GrainType, SoilWs, f=0, FR=0, ClaySu=0, TrenchDepth=0, TrenchAng=45):
        self.SoilType = SoilType                # Select from 'Clay' or 'Sand'
        self.GrainType = GrainType              # 1 to 8 index as Table 3-1 of [1]
        self.ID = self.SetID(self.SoilType)
        self.SandGZ = SGZ[GrainType-1]          # Grain Size, mm
        self.d50 = self.SandGZ/1000             # Grain Size d50, m
        self.kb = self.d50*2.5                  # Nikuradse's parameter
        self.z0 = SRH[GrainType-1]              # Roughness, m
        self.ClaySu = ClaySu                    # Clay Undrained Shear Strength, Pa
        self.SoilWs = SoilWs                    # Soil Submerged Unit Weight or Dry Unit Weight for CLAY, N/m3
        self.TrenchDepth = TrenchDepth          # Trench Depth, m
        self.TrenchAng = TrenchAng              # Trench Angle, deg
        self.f = self.SetFriction(self.ID,f)
        self.FR = FR

    def SetID(self,SoilType):
        if (SoilType=='CLAY')or(SoilType=='Clay')or(SoilType=='clay'):
            ID = 1
        elif (SoilType=='SAND')or(SoilType=='Sand')or(SoilType=='sand'):
            ID = 2
        else:
            ID = 0
        return ID

    def SetSandGZ(self,GZ):
        if self.ID ==1 :
            x= 0.0625
        else :
            x = SGZ[GZ-1]
        return x

    def SetFriction(self,ID,f):
        if self.ID ==1 :
            ff= 0.2
        else :
            ff = 0.6
        if f==0:
            return ff
        else :
            return f


    def ShowData(self):
        print(' ')
        print('------------INPUT-------------')
        print('Class Name: ',self.__class__.__name__)
        print('SoilType: ',self.SoilType )
        print('ID: {0:.0f}'.format(self.ID) )
        print('GrainType: ',self.GrainType )
        print('------------CALCULATION-------------')
        print('Grain Size: {:.4E} mm'.format(self.SandGZ) )
        print('Grain Size, d50: {:.4E} m'.format(self.d50) )
        print("Nikuradse's parameter, kb: {:.4E} m".format(self.kb) )
        print('Roughness, z0: {:.4E} m'.format(self.z0) )
        print('ClaySu: {0:.3f} Pa'.format(self.ClaySu) )
        print('Submerged Weight: {0:.3f} N/m3'.format(self.SoilWs) )
        print('Trench Depth: {0:.3f} m'.format(self.TrenchDepth) )
        print('Trench Angle: {0:.3f} deg'.format(self.TrenchAng) )
        print('Friction Factor: {0:.3f} deg'.format(self.f) )
        print('Passive Soil Resistance: {0:.3f} deg'.format(self.FR) )
        print(' ')

# Class for on-bottom stability analysis DNVGL RP F109
class DNVGLRPF109:
    count = 0
    def __init__(self, pl, en, sb):
        self.FlowFactor = self.CalFlowFactor(pl.OD,en.Zr,sb.z0,en.envDir)
        self.CL = pl.CL                                 # Lift coefficient
        self.Wden = en.seaDen                           # Water density, kg/m3
        self.UD = en.Ur*self.FlowFactor                 # Mean current veolicy perperndicular to pipe, m/s, Eq 3.3 of [1]
        self.FZ = self.CalFZ(en.seaDen,pl.OD,self.CL,en.Uss,self.UD)
        self.zp = pl.zp
        self.ztr = sb.TrenchDepth
        self.ZPD = self.zp/pl.OD
        self.ZTD = self.ztr/pl.OD
        self.Voc = self.UD
        self.Koc = en.Uoc*en.Toc/pl.OD
        self.Moc = self.Voc/en.Uoc

        self.FR = sb.FR

        self.r_perm_y = 1.0
        self.r_perm_z = 0.7                                                 # Eq 3.18 of [1]
        self.r_pen_y = self.Cal_r_pen_y(self.zp, pl.OD)                     # Eq 3.19 of [1]
        self.r_pen_z = self.Cal_r_pen_z(self.zp, pl.OD)                     # Eq 3.20 of [1]
        self.r_tr_y = self.Cal_r_tr_y(pl.OD, sb.TrenchDepth, sb.TrenchAng)  # Eq 3.21 of [1]
        self.r_tr_z = self.Cal_r_tr_z(pl.OD, sb.TrenchDepth, sb.TrenchAng)  # Eq 3.22 of [1]
        self.r_tot_y = self.r_perm_y*self.r_pen_y*self.r_tr_y               # Eq 3.17 of [1]
        self.r_tot_z = self.r_perm_z*self.r_pen_z*self.r_tr_z               # Eq 3.17 of [1]

        self.CY = fCYMK(self.Moc,self.Koc)                                  # Peak horizontal load coefficient, Table 3-9 of [1]
        self.CZ = fCZMK(self.Moc,self.Koc)                                  # Peak vertical load coefficient, Table 3-10 of [1]

        self.FYY = self.Cal_PeakLoad(self.r_tot_y, self.Wden, pl.OD, self.CY, en.Uoc, self.Voc) # Peak horizontal load, N/m, Eq 3.40 of [1]
        self.FZZ = self.Cal_PeakLoad(self.r_tot_z, self.Wden, pl.OD, self.CZ, en.Uoc, self.Voc) # Peak horizontal load, N/m, Eq 3.41 of [1]

        self.UCY = self.Cal_UCY(pl.GammaSC, self.FYY, sb.f, self.FZZ, pl.Ws, self.FR)   # Unity value in Y direction, -, Eq 3.38 of [1]
        self.UCZ = self.Cal_UCZ(pl.GammaSC, self.FZZ, pl.Ws)                            # Unity value in Z direction, -, Eq 3.39 of [1]
        # self.Json = self.outputJson


    # This function return mean permendiculra current velocity factor, Eq 3.3 of [1]
    def CalFlowFactor(self, D,zr,z0,Ang):
        A = 1/(log(zr/z0+1))
        B = 1+z0/D
        C = log(D/z0+1)
        return A*(B*C-1)*sin(DtR(Ang))

    def CalFZ(self, Pden, D, CL, Us, UD):
        U = (Us+UD)
        return 0.5*Pden*D*CL*U**2

    # This function return the load reduction due to penetration in y direction, Eq 3.19 of [1]
    def Cal_r_pen_y(self, zp, D):
        return max(0.3, (1-1.4*zp/D))

    # This function return the load reduction due to penetration in z direction, Eq 3.20 of [1]
    def Cal_r_pen_z(self, zp, D):
        return max(0, (1-1.3*(zp/D-0.1)))

    # This function return the load reduction due to trenching in y direction, Eq 3.21 of [1]
    def Cal_r_tr_y(self,D ,ztr, TrenchAng):
        return 1-0.18*(TrenchAng-5)**0.25*(ztr/D)**0.42

    # This function return the load reduction due to trenching in z direction, Eq 3.22 of [1]
    def Cal_r_tr_z(self,D, ztr, TrenchAng):
        return 1-0.14*(TrenchAng-5)**0.43*(ztr/D)**0.46

    # This function return the unity value in Y direction, Eq 3.38 of [1]
    def Cal_UCY(self,Gamma,FY,f,FZ,Ws,FR):
        return Gamma*(FY+f*FZ)/(f*Ws+FR)

    # This function return the unity value in Z direction, Eq 3.39 of [1]
    def Cal_UCZ(self,Gamma,FZ,Ws):
        return Gamma*FZ/Ws

    # This function return peakload, Eq 3.40 and Eq 3.41 of [1]
    def Cal_PeakLoad(self,rtot,Wden,D,Cy,U,V):
        return rtot*0.5*Wden*D*Cy*(U+V)**2

    def ShowData(self):
        print(' ')
        print('------------INPUT-------------')
        print('Class Name: ',self.__class__.__name__)
        print('CL: {0:.3f} '.format(self.CL) )
        print('Water density: {0:.3f} kg/m3'.format(self.Wden) )
        print('zp: {0:.3f} m'.format(self.zp) )
        print('ztr: {0:.3f} m'.format(self.ztr) )
        print('zp/D: {0:.3f} m'.format(self.ZPD) )
        print('ztr/D: {0:.3f} m'.format(self.ZTD) )
        print('------------CALCULATION-------------')
        print('FlowFactor Over Pipe: {0:.3f}',self.FlowFactor )
        print('UD: {0:.3f} m/s'.format(self.UD) )
        print('FZ: {0:.3f} N/m'.format(self.FZ) )
        print('Voc: {0:.3f} m/s'.format(self.Voc) )
        print('Koc: {0:.3f} '.format(self.Koc) )
        print('Moc: {0:.3f} '.format(self.Moc) )
        print('FR : {0:.3f} N/m'.format(self.FR) )
        print('r_perm_y: {0:.3f} '.format(self.r_perm_y) )
        print('r_perm_z: {0:.3f} '.format(self.r_perm_z) )
        print('r_pen_y: {0:.3f} '.format(self.r_pen_y) )
        print('r_pen_z: {0:.3f} '.format(self.r_pen_z) )
        print('r_tr_y: {0:.3f} '.format(self.r_tr_y) )
        print('r_tr_z: {0:.3f} '.format(self.r_tr_z) )
        print('r_tot_y: {0:.3f} '.format(self.r_tot_y) )
        print('r_tot_z: {0:.3f} '.format(self.r_tot_z) )
        print('CY: {0:.3f} '.format(self.CY) )
        print('CZ: {0:.3f} '.format(self.CZ) )
        print('FYY: {0:.3f} '.format(self.FYY) )
        print('FZZ: {0:.3f} '.format(self.FZZ) )
        print('UCY: {0:.3f} '.format(self.UCY) )
        print('UCZ: {0:.3f} '.format(self.UCZ) )
        print(' ')

    def jSon(self):
        input = {
            'Class Name': self.__class__.__name__
        }

        output = {
            'Keulegan-Carpenter number for single design oscilation, K*, -' : fFormat.format(self.Koc, 2),
            'Steady to oscillatory veolcity ratio for single design oscillation, M*,-' : fFormat.format(self.Moc, 2),
            'Load reduction due to penetration in horizontal direction, rpenz,-' : fFormat.format(self.r_pen_y.real, 2),
            'Load reduction due to trenching in horizontal direction, rtrz,-' : fFormat.format(self.r_tr_y.real, 2),
            'Total reduction factor in horizontal direction, rtot,z, -' : fFormat.format(self.r_tot_y.real, 2),
            'Peak horizontal load coefficient CY*, N/m' : fFormat.format(self.CY.real, 2),
            'Peak horizontal load FY*, N/m' : fFormat.format(self.FYY.real, 2),
            'Unity in horizontal direction, UCY, -': fFormat.format(self.UCY.real, 2),
            'Load reduction due to permeable seabed in vertical direction, rpermz,-' : fFormat.format(self.r_perm_z.real, 2),
            'Load reduction due to penetration in vertical direction, rpenz,-' : fFormat.format(self.r_pen_z.real, 2),
            'Load reduction due to trenching in vertical direction, rtrz,-' : fFormat.format(self.r_tr_z.real, 2),
            'Total reduction factor in vertical direction, rtot,z, -' : fFormat.format(self.r_tot_z.real, 2),
            'Peak vertical load coefficient, CZ*, N/m' : fFormat.format(self.CZ.real, 2),
            'Peak vertical load, FZ*, N/m' : fFormat.format(self.FZZ.real, 2),
            'Unity in vertical direction, UCZ, -': fFormat.format(self.UCZ.real, 2),
        }

        result = [input, output]

        return result





#*****************************************************
# MAIN
#**********************************************

# Input

# Input for TPipeInput
plOD = 0.273                        # Outer diameter, m
plWS = 2831.11                      # Pipe submerged weight, N/m
plGammaSC = 1.0                     # Safety class factor, -
plZp = 0.01                         # Initial pipe penetration, m, default = 0
plCL = 0.9                          # Pipe lift coefficient, -, default = 0.9

# Input for TSeabedOBT
sbSoilType = 'Sand'                 # Soil type, select from clay or sand
sbGrainType = 3                     # Select from 1 to 8 (Table 3-1)
sbSubWeight = 12500                 # Soil submerged weight, N/Cu.m.
sbf = 0.50                          # Soil friction coeifficeint, -, Default = 0
sbFR = 350                          # Soil resistance N/m, Default = 0 N/m
sbSu = 0                            # Undrained shear strength, Pa, Default = 0 Pa
sbTrenchDepth = 0.02                # Trench depth, m, Default = 0 m
sbTrenchAngle = 45                  # Trench angle, deg, Default = 45 degree

# Input for TEnvForOBT
enWD = 60                           # Water depth, m
enHs = 12                           # Significant wave height, m
enTp = 11                           # Peak wave period, sec
enJSW = 1.0                         # JONSWAP wave parameter, -
enUr = 0.5                          # Current velocity, m/s
enZr = 3.0                          # Reference wave height, m
enSeaDen = 1025                    # Seawater density, kg/cu.m., default 1025 kg/cu.m.
enEnvDir = 90                      # Environmental direction, degree, default = 90 degree
enWaveDir = 90                      # Wave direction, degree, default = 90 degree
enRD = 0                            # Reduction factor due to spectral direcctinality and spreading, -, default = 0
enNs = 8                            #



#print('len(argv) = ',len(sys.argv))

if len(sys.argv) > 1:
    #print(sys.argv)
    #print('len(sys.argv) = ',len(sys.argv))

    plOD = float(sys.argv[1])
    plWS = float(sys.argv[2])
    plGammaSC = float(sys.argv[3])
    plZp = float(sys.argv[4])
    plCL = float(sys.argv[5])
    sbGrainType = int(sys.argv[6])
    sbf = float(sys.argv[7])
    sbFR = float(sys.argv[8])
    sbTrenchDepth = float(sys.argv[9])
    sbTrenchAngle = float(sys.argv[10])
    enWD = float(sys.argv[11])
    enHs = float(sys.argv[12])
    enTp = float(sys.argv[13])
    enJSW = float(sys.argv[14])
    enUr = float(sys.argv[15])
    enZr = float(sys.argv[16])
    enSeaDen = float(sys.argv[17])
    enEnvDir = float(sys.argv[18])



pipe = TPipeInput(
    OD = plOD,
    Ws = plWS,
    GammaSC = plGammaSC,
    zp = plZp,
    CL = plCL,
    )

seabed = TSeabedOBT(
    SoilType = sbSoilType,
    GrainType = sbGrainType,
    SoilWs = sbSubWeight,
    f = sbf,
    FR = sbFR,
    ClaySu = sbSu,
    TrenchDepth = sbTrenchDepth,
    TrenchAng = sbTrenchAngle,
    )
#pipe.ShowData()
#seabed.ShowData()
'''
print('enWD ',enWD)
print('enHs ',enHs)
print('enTp ',enTp)
print('enJSW ',enJSW)
print('enUr ',enUr)
print('enZr ',enZr)
print('enSeaDen ',enSeaDen)
print('enEnvDir ',enEnvDir)
'''
envi = TEnvForOBT(
    WDepth = enWD,
    Hs = enHs,
    Tp = enTp,
    JSW = enJSW,
    Ur = enUr,
    Zr = enZr,
    seaDen = enSeaDen,
    envDir = enEnvDir,
    WaveDir = enWaveDir,
    RD = enRD,
    ns = enNs)

#print('A')
Result = DNVGLRPF109(pipe,envi,seabed)
#print(Result.jSon())
#final = Result.jSon()
print(json.dumps(Result.jSon()))#[1])
