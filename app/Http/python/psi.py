#!/usr/bin/python;
import sys
import ast

import math as m
import numpy as np
from scipy.interpolate import interp1d
from scipy.optimize import fsolve

# Version Controller
sTitle = 'DNVGL RP F114 Pipeline Soil Interaction analysis'
sVersion = '1.0.0'

# Define constants
pi = m.pi
e = m.e

precision = 2
fFormat = "{:.{}f}"
separate = "--------------------"

#Define default values
#Pipeline properties
#Outer diameter, m
d_D = 0.504
#Submerged weight of pipe, N/m
d_W = [211.05, 365.18, 241.13]
#Modulus of elasticity of pipe
d_E = 2.07e+11
#Moment of inertia
d_I = 8.82e-05
#Installation tension, N
d_T0 = 1000

#Soil properties

#Submerged unit weigth, N/Cu.m.
d_gamma = np.array([
    [0, 5000],
    [1, 5000],
    [2, 5000],
    [3, 5000],
    [4, 5000],
    ])
#Intact shear strength, N/Sq.m.
d_su = np.array([
    [0, 1000],
    [1, 2000],
    [2, 3000],
    [3, 4000],
    [4, 5000],
    ])

#Remould shear strength
d_su_re = np.array([
    [0, 400],
    [1, 800],
    [2, 1200],
    [3, 1600],
    [4, 2000],
    ])

#Friction angle
d_phi = np.array([
    [0, 30],
    [1, 31],
    [2, 32],
    [3, 33],
    [4, 34],
    ])

#Peak interface angle
d_deltaPeak  = np.array([
    [0, 25],
    [1, 26],
    [2, 27],
    [3, 28],
    [4, 29],
    ])

#Resiual interface angle
d_deltaResidual  = np.array([
    [0, 20],
    [1, 21],
    [2, 22],
    [3, 23],
    [4, 24],
    ])

#PSI calculation default
d_alpha = 1
#surface input code 1 is rough surface, 2 is the smooth surface, default is 1
d_surface = 1
#gamma rate
d_gammaRate = 1
#default weight offset, 0 N
d_Woffset = 0
#default pipe weight, 1000 N/m
d_Wpipe = 1000

d_vMethod = 'UD2'

def N_DNVF114(phi:float):
    phiRad = m.radians(phi)
    rad45 = m.radians(45)
    Nq = m.exp(pi*m.tan(phiRad))*((m.tan(rad45 + 0.5*phiRad))**2)
    Ng1 = m.fabs(1.5 * (Nq - 1) * m.tan(phiRad))
    Ng2 = m.fabs(2.0 * (Nq - 1) * m.tan(phiRad))
    return [Ng1, Ng2, Nq];

# This function return the curve of DNVGL RP F114 Figure 4.4
def Figure44():
    Figure44 = np.array([
    [0.000,1.00,1.000],
    [0.075,1.02,1.006],
    [0.226,1.05,1.024],
    [0.414,1.09,1.047],
    [0.527,1.12,1.061],
    [0.565,1.13,1.065],
    [0.715,1.17,1.081],
    [0.866,1.19,1.097],
    [1.017,1.22,1.112],
    [1.318,1.27,1.142],
    [1.356,1.28,1.146],
    [1.657,1.31,1.164],
    [1.921,1.34,1.180],
    [1.958,1.35,1.183],
    [2.259,1.38,1.199],
    [2.523,1.40,1.213],
    [2.561,1.40,1.215],
    [2.900,1.43,1.232],
    [3.126,1.45,1.243],
    [3.238,1.46,1.248],
    [3.577,1.47,1.263],
    [3.841,1.49,1.274],
    [3.879,1.49,1.275],
    [4.218,1.51,1.285],
    [4.444,1.52,1.292],
    [4.707,1.54,1.301],
    [5.084,1.55,1.313],
    [5.121,1.55,1.314],
    [5.498,1.57,1.322],
    [5.799,1.58,1.329],
    [5.912,1.58,1.331],
    [6.326,1.59,1.339],
    [6.402,1.60,1.341],
    [6.816,1.61,1.349],
    [7.042,1.61,1.353],
    [7.494,1.62,1.364],
    [7.682,1.63,1.368],
    [7.946,1.63,1.370],
    [8.360,1.64,1.374],
    [8.548,1.64,1.377],
    [9.000,1.65,1.383],
    [9.113,1.65,1.385],
    [9.640,1.66,1.391],
    [9.715,1.66,1.392],
    [10.205,1.68,1.397],
    [10.318,1.68,1.398],
    [10.732,1.68,1.404],
    [10.770,1.68,1.405],
    [11.222,1.69,1.409],
    [11.335,1.69,1.411],
    [11.787,1.69,1.413],
    [11.862,1.69,1.414],
    [12.163,1.70,1.415],
    [12.389,1.70,1.417],
    [12.766,1.71,1.423],
    [12.916,1.71,1.426],
    [13.331,1.71,1.430],
    [13.557,1.71,1.432],
    [13.895,1.72,1.432],
    [13.971,1.72,1.432],
    [14.423,1.72,1.435],
    [14.498,1.72,1.435],
    [14.950,1.72,1.438],
    [15.402,1.73,1.441],
    [15.439,1.73,1.441],
    [15.778,1.73,1.444],
    [16.004,1.73,1.446],
    [16.080,1.73,1.447],
    ])

    return Figure44;

# This function return the curve of DNVGL RP F114 Figure 4.11
def Figure411():
    Figure411 = np.array([
    [0.000,0.50,0.250],
    [0.567,0.50,0.249],
    [1.357,0.49,0.247],
    [2.544,0.48,0.243],
    [2.678,0.48,0.242],
    [4.522,0.46,0.234],
    [5.320,0.45,0.232],
    [6.500,0.44,0.228],
    [6.860,0.44,0.226],
    [8.401,0.43,0.221],
    [8.477,0.43,0.221],
    [9.940,0.42,0.217],
    [10.454,0.42,0.216],
    [11.699,0.41,0.214],
    [12.650,0.41,0.213],
    [14.558,0.40,0.208],
    [14.626,0.40,0.208],
    [16.536,0.39,0.205],
    [16.822,0.39,0.204],
    [18.513,0.38,0.202],
    [19.017,0.38,0.201],
    [20.491,0.37,0.200],
    [20.993,0.37,0.199],
    [22.248,0.37,0.198],
    [23.189,0.36,0.196],
    [24.006,0.36,0.196],
    [25.383,0.36,0.194],
    [25.983,0.36,0.193],
    [27.360,0.35,0.191],
    [27.960,0.35,0.191],
    [29.555,0.35,0.189],
    [29.935,0.35,0.189],
    [31.750,0.34,0.188],
    [31.912,0.34,0.188],
    [33.725,0.34,0.186],
    [34.107,0.34,0.186],
    [35.920,0.34,0.184],
    [36.084,0.34,0.184],
    [38.060,0.33,0.184],
    [38.114,0.33,0.184],
    [40.255,0.33,0.183],
    [40.309,0.33,0.183],
    [42.231,0.33,0.181],
    [42.504,0.33,0.181],
    [44.426,0.33,0.181],
    [44.699,0.33,0.181],
    [46.621,0.32,0.180],
    [46.674,0.32,0.180],
    [48.597,0.32,0.180],
    [48.869,0.32,0.180],
    [50.792,0.32,0.178],
    [51.064,0.32,0.178],
    [52.987,0.32,0.178],
    [53.258,0.32,0.178],
    [55.182,0.32,0.176],
    [55.453,0.32,0.176],
    [57.158,0.31,0.176],
    [57.647,0.31,0.176],
    [59.353,0.31,0.176],
    [59.622,0.31,0.176],
    [61.378,0.31,0.175],
    [61.548,0.31,0.175],
    [63.573,0.31,0.175],
    [63.743,0.31,0.175],
    [65.718,0.31,0.175],
    [65.767,0.31,0.175],
    [67.913,0.31,0.173],
    [67.962,0.31,0.173],
    [70.108,0.31,0.173],
    [70.156,0.31,0.173],
    [72.303,0.30,0.173],
    [72.351,0.30,0.173],
    [74.326,0.30,0.171],
    [74.498,0.30,0.171],
    [76.521,0.30,0.171],
    [76.692,0.30,0.171],
    [78.667,0.30,0.171],
    [78.715,0.30,0.171],
    [80.862,0.30,0.171],
    [80.909,0.30,0.171],
    [83.056,0.30,0.171],
    [83.104,0.30,0.171],
    [85.079,0.30,0.170],
    [85.251,0.30,0.170],
    [87.227,0.30,0.170],
    [87.273,0.30,0.170],
    [89.421,0.30,0.170],
    [89.468,0.30,0.170],
    [91.615,0.30,0.170],
    [91.662,0.30,0.170],
    [93.591,0.30,0.170],
    [93.857,0.30,0.170],
    [95.785,0.30,0.170],
    [96.051,0.30,0.170],
    [97.980,0.30,0.170],
    [98.245,0.30,0.170],
    [100.175,0.29,0.170],
    [100.220,0.29,0.170],
    [102.369,0.29,0.168],
    [102.415,0.29,0.168],
    [104.564,0.29,0.168],
    [104.609,0.29,0.168],
    [106.758,0.29,0.168],
    [106.804,0.29,0.168],
    [108.953,0.29,0.168],
    [108.998,0.29,0.168],
    [110.928,0.29,0.168],
    [111.193,0.29,0.168],
    [112.903,0.29,0.168],
    [113.387,0.29,0.168],
    [115.097,0.29,0.166],
    [115.362,0.29,0.166],
    [117.292,0.29,0.166],
    [117.557,0.29,0.166],
    [119.486,0.29,0.166],
    [119.751,0.29,0.166],
    [121.681,0.29,0.166],
    [121.945,0.29,0.166],
    [123.875,0.29,0.166],
    [124.140,0.29,0.166],
    [126.070,0.29,0.166],
    [126.334,0.29,0.166],
    [128.045,0.29,0.166],
    [128.529,0.29,0.166],
    [130.240,0.29,0.166],
    [130.723,0.29,0.166],
    [132.434,0.29,0.166],
    [132.917,0.29,0.166],
    [134.628,0.29,0.166],
    [135.112,0.29,0.166],
    [136.823,0.29,0.166],
    [137.306,0.29,0.166],
    [139.017,0.29,0.166],
    ])

    return Figure411;

# This function return the curve of DNVGL RP F114 Figure 4.11
def Figure420():

    Figure420 = np.array([
    [0, 1.73063, 1.94676, 2.11728, 2.44136],
    [0.0961538, 1.85355, 2.07407, 2.28704, 2.61111],
    [0.0979021, 1.85579, 2.07711, 2.29012, 2.6142],
    [0.0996503, 1.85802, 2.08014, 2.29348, 2.61837],
    [0.195804, 1.94141, 2.24691, 2.47786, 2.84768],
    [0.197552, 1.94293, 2.24952, 2.48122, 2.85185],
    [0.199301, 1.94444, 2.25213, 2.48457, 2.85632],
    [0.297203, 2.11132, 2.39815, 2.67901, 3.10664],
    [0.298951, 2.1143, 2.40162, 2.68311, 3.11111],
    [0.300699, 2.11728, 2.40509, 2.68721, 3.1168],
    [0.395105, 2.22145, 2.59259, 2.90847, 3.42381],
    [0.398601, 2.22531, 2.59855, 2.91667, 3.43519],
    [0.496503, 2.38936, 2.76543, 3.16699, 3.81066],
    [0.5, 2.39522, 2.77276, 3.17593, 3.82407],
    [0.501748, 2.39815, 2.77642, 3.1821, 3.83203],
    [0.597902, 2.56492, 2.97782, 3.5216, 4.26982],
    [0.59965, 2.56796, 2.98148, 3.5271, 4.27778],
    [0.601399, 2.57099, 2.98565, 3.53259, 4.28574],
    [0.699301, 2.72222, 3.21914, 3.84019, 4.73148],
    [0.701049, 2.72552, 3.22338, 3.84568, 4.74079],
    [0.797203, 2.90678, 3.45679, 4.18519, 5.25298],
    [0.798951, 2.91008, 3.46052, 4.19136, 5.26229],
    [0.800699, 2.91337, 3.46424, 4.19657, 5.2716],
    [0.802448, 2.91667, 3.46797, 4.20179, 5.27919],
    [0.898601, 3.02276, 3.67284, 4.48861, 5.69612],
    [0.90035, 3.02469, 3.67549, 4.49383, 5.7037],
    [0.996503, 3.19444, 3.82142, 4.7023, 6.00617],
    [0.998252, 3.19753, 3.82407, 4.70609, 6.01167],
    [1, 3.20062, 3.82673, 4.70988, 6.01717],
    ])

    return Figure420;

# Equation 4.3 of DNVGL RP F114
def B_zd(z:float, D:float):

    criterion = 0.5*D
    if z < criterion:
        B_zd = 2 * m.sqrt(D*z - z**2)
    else:
        B_zd = D
    return B_zd;

# Equation 4.7 of DNVGL RP F114
def Abm_zd(z:float, D:float):

    B = B_zd(z,D)
    criterion = 0.5*D

    if z < criterion:
        Abm_zd = 0.25*m.asin(B/D)*D**2 - B*0.25*D*m.cos(m.asin(B/D))
    else:
        Abm_zd = 0.125*pi*D**2 + D*(z-0.5*D)
    return Abm_zd;

# Equation 4.26 of DNVGL RP F114
def Aberm_zd(z:float, D:float, x:float=0):


    Abm = Abm_zd(z,D)
    Aberm_zd = 0.5*Abm + x*z

    return Aberm_zd;

# This function return the value in accodance with equation 4.4 of DNVGL RP F114
def zsu0_zd(z:float, D:float):

    B = B_zd(z,D)
    criterion = 0.5*D*(1-0.7071)

    if z < criterion:
        zsu0_zd = 0
    else:
        zsu0_zd = z+0.5*D*(1.4142 - 1) - 0.5*B
    return zsu0_zd;

# This fucntion return depth correction factor in accodance with equation 4.6 of DNVGL RP F114
def dca_zd(z:float, D:float, Qv0:float):
    B = B_zd(z,D)
    zsu0 = zsu0_zd(z,D)
    suz0 = np.interp(0,su[:,0],su[:,1])
    su0 = np.interp(zsu0,su[:,0],su[:,1])
    su1 = 0.5*(suz0 + su0)
    su2 = Qv0/(B*5.14)
    dca_zd = 0.3*(su1/su2)*m.atan(zsu0/B)

    return dca_zd;

# This fucntion return reference depth in accodance with equation 4.10 of DNVGL RP F114
def zo_zdphi(z:float, d:float, phi:float):

    B = B_zd(z,D)
    angle = 0.25*pi - 0.50*m.radians(phi)
    constant = 0.5*D*(1-m.cos(angle))

    if z < constant:
        zo_zdphi = 0
    else:
        zo_zdphi = z - 0.5*D + (((D/2)/(m.sin(angle)))-(B/2))*m.tan(angle)

    return zo_zdphi;

# This fucntion return reference depth in accodance with equation 4.11 of DNVGL RP F114
def dq_zdphi(z:float, d:float, phi:float):

    B = B_zd(z,D)
    zo = zo_zdphi(z,d,phi)
    dq_zdphi = 1 + 1.2*(zo/B)*m.tan(phi)*((1-m.sin(phi))**2)

    return dq_zdphi;

# This function return vertical response in accodance with equation 4.1 and 4.2 of DNVGL RP F114
def QvUndrainM1(z:float, D:float, su:float=d_su, gamma:float=d_gamma, surface:float=d_surface, Woffset:float=d_Woffset):

    x1 = z
    x2 = z + 3*D
    y1 = np.interp(x1,su[:,0],su[:,1])
    y2 = np.interp(x2,su[:,0],su[:,1])
    rho = (y2-y1)/(x2-x1)
    F = np.interp(z,Figure44()[:,0],Figure44()[:,surface])
    Nc = 5.14
    su_z = np.interp(z,su[:,0],su[:,1])
    B = B_zd(z,D)

    Qv0 = F*(Nc*su_z + 0.25*rho*B)*B

    Abm = Abm_zd(z,D)
    dca = dca_zd(z,D,Qv0)
    gamma_z = np.interp(z,gamma[:,0],gamma[:,1])

    QvUndrainM1 = Qv0*(1+dca) + gamma_z*Abm - Woffset

    return QvUndrainM1;

# This function return vertical response in accodance with equation 4.8 of DNVGL RP F114
def QvUndrainM2(z:float, D:float, su:float, gamma:float, Woffset:float):

    Abm = Abm_zd(z,D)
    su_z = np.interp(z,su[:,0],su[:,1])
    gamma_z = np.interp(z,gamma[:,0],gamma[:,1])

    A = min([ 6*(z/D)**0.25, 3.4*m.sqrt(10*z/D)])
    B = (1.5*gamma_z*Abm)/(D*su_z)

    QvUndrainM2 = (A+B)*D*su_z - Woffset

    # print (QvUndrainM2)

    return QvUndrainM2;

# This function return vertical response in accodance with equation 4.9 of DNVGL RP F114
def QvDrain(z:float, D:float, phi:float=d_phi, gamma:float=d_gamma, Woffset:float=d_Woffset):

    phi_z = np.interp(z,phi[:,0],phi[:,1])
    gamma_z = np.interp(z,gamma[:,0],gamma[:,1])
    B = B_zd(z,D)
    Ng = N_DNVF114(phi_z)[0]
    Nq = N_DNVF114(phi_z)[1]
    z0 = zo_zdphi(z,D,phi_z)
    dq = dq_zdphi(z,D,phi_z)
    QvDrain = 0.5*gamma_z*Ng*B**2 + z0*gamma_z*Nq*dq*B - Woffset

    return QvDrain;

# This function return vertical response in accodance with equation 4.2, 4.8 and 4.9 of DNVGL RP F114
def QvAll(z:float, D:float, method:str, su:float, phi:float, gamma:float, surface:float, Woffset:float):

    if method == 'UD1':
        QvAll = QvUndrainM1(z, D, su, gamma, surface, Woffset)
    elif method == 'UD2':
        QvAll = QvUndrainM2(z, D, su, gamma, Woffset)
    elif method == 'D':
        QvAll = QvDrain(z, D, phi, gamma, Woffset)

    return QvAll;

# This function return embedment of pipeline using Eq 4.12 of DNVGL RP F114
def klay(z:float, D:float, method:str, su:float=d_su, phi:float=d_phi, gamma:float=d_gamma, surface:float=d_surface, Woffset:float=d_Woffset, Wpipe:float=d_Wpipe, I:float=d_I, E:float=d_E, T0:float=d_T0 ):

    QV = QvAll(z, D, method, su, phi, gamma, surface, Woffset)

    klay1 = QV/Wpipe
    klay2 = 0.6 + 0.4*(((E*I*klay1*Wpipe)/(z*T0**2))**0.25)
    klay = klay1-klay2

    return klay;

# This function return beta angle of pipeline using Figure 4-12 of DNVGL RP F114.
def beta_zd(z:float, D:float):

    criterion = 0.5*D
    if z < criterion:
        beta_zd = m.acos(1-(2*z/D))
    else:
        beta_zd = 0.5*pi

    return beta_zd;

# This function return wedging factor of pipeline using Figure 4-12 of DNVGL RP F114
def WF_zd(z:float, D:float):

    beta = beta_zd(z,D)
    WF_zd = (2*m.sin(beta))/(beta + m.sin(beta)*m.cos(beta))

    return WF_zd;

# This function return the effective stress for given depth
def Seff_zgamma(z:float, gamma:float):

    N = 100
    Seff_zgamma = 0
    dz = z/N

    for n in range(0,N):

        gamma_z = np.interp(z,gamma[:,0],gamma[:,1])
        Seff_zgamma = Seff_zgamma + gamma_z*dz

    return Seff_zgamma;

# This function return te undrained lateral breakout resistance in accodance with DNVGL RP F114 Equation 4.15
def FAxeBrkUnd(z:float, D:float, alpha:float=d_alpha, su:float=d_su, gamma:float=d_gamma, OCR:float=1, m:float=0.775, gammaRate:float=d_gammaRate, V:float=d_Wpipe):

    # print(z, D, alpha, su, gamma, OCR, gammaRate, V)

    su_z = np.interp(z,su[:,0],su[:,1])
    Seff_z = Seff_zgamma(z, gamma)
    WF = WF_zd(z, D)
    # SuOSeff = su_z/Seff_z
    SuOSeff_HE = np.interp(Seff_z/1000,Figure411()[:,0],Figure411()[:,1])
    SuOSeff_LE = np.interp(Seff_z/1000,Figure411()[:,0],Figure411()[:,2])
    SuOSeff = 0.5*(SuOSeff_HE + SuOSeff_LE)

    FAxeBrkUnd = alpha*SuOSeff*(OCR**m)*WF*gammaRate*V
    # print(SuOSeff[0],WF,gammaRate, FaBrkUnd)

    return FAxeBrkUnd;

# This function return te undrained lateral breakout resistance in accodance with DNVGL RP F114 Equation 4.16 and Eq 4.18
def FAxeDra(z:float, D:float, delta:float=d_deltaPeak, V:float=d_Wpipe):

    WF = WF_zd(z,D)
    delta_z = m.radians(np.interp(z,delta[:,0],delta[:,1]))
    FAxeDra = m.tan(delta_z)*WF*V

    return FAxeDra;

def DAxe(D:float):

    DAxeBi = np.array([
        min([1.25e-03, 0.0025*D]),
        min([5.00e-03, 0.0100*D]),
        min([2.50e-01, 0.0500*D]),
    ])


    DAxeTri = np.array([
        np.array([
        min([1.25e-03, 0.0025*D]),
        min([5.00e-03, 0.0100*D]),
        min([5.00e-02, 0.1000*D]),
        ]),
        np.array([
        min([7.50e-03, 0.0150*D]),
        min([3.00e-02, 0.0600*D]),
        min([2.50e-01, 0.5000*D]),
        ])
    ])

    DAxe = [DAxeBi, DAxeTri]

    return DAxe;

# This function return te undrained lateral breakout resistance in accodance with DNVGL RP F114 Equation 4.22
def FLatBrkUndM1(z:float, D:float, alpha:float=d_alpha, su:float=d_su, gamma:float=d_gamma, OCR:float=1, m:float=0.775, gammaRate:float=d_gammaRate, V:float=d_Wpipe, ka:float=2, kp:float=2.5):

    su_z = np.interp(z,su[:,0],su[:,1])
    gamma_z = np.interp(z,gamma[:,0],gamma[:,1])
    Seff_z = Seff_zgamma(z, gamma)
    WF = WF_zd(z, D)
    SuOSeff = su_z/Seff_z

    FLatBrkUndM1_1 = alpha*SuOSeff*(OCR**m)*WF*gammaRate*V

    su_ave = np.interp(0.5*z,su[:,0],su[:,1])

    FLatBrkUndM1_2a = z*(ka*su_ave + kp*su_ave)*gammaRate
    FLatBrkUndM1_2b = z*(ka*su_ave + 0.5*gamma_z*z)*gammaRate

    FLatBrkUndM1 = [FLatBrkUndM1_1+FLatBrkUndM1_2a, FLatBrkUndM1_1+FLatBrkUndM1_2b]

    return FLatBrkUndM1;

# This function return te undrained lateral breakout resistance in accodance with DNVGL RP F114 Equation 4.22
def FLatBrkUndM2(z:float, D:float, su:float=d_su, gamma:float=d_gamma, V:float=d_Wpipe):

    su_z = np.interp(z,su[:,0],su[:,1])
    gamma_z = np.interp(z,gamma[:,0],gamma[:,1])

    T1 = 1.7*(z/D)**0.61
    T2 = 0.23*((V/(su_z*D))**0.83)
    T3 = 0.6*(gamma_z*D/su_z)*((z/D)**2)

    FLatBrkUndM2 = su_z*D*(T1 + T2 + T3)

    return FLatBrkUndM2;

# # This function return te undrained lateral breakout resistance in accodance with DNVGL RP F114 Equation 4.22
# def FLatBrkDraM1(z:float, D:float, su:float=d_su, gamma:float=d_gamma, V:float=d_Wpipe):
#
#     su_z = np.interp(z,su[:,0],su[:,1])
#     gamma_z = np.interp(z,gamma[:,0],gamma[:,1])
#
#     FLatBrkDPassive = 0.5*kp*gamma_z*z**2
#     FLatBrkDFric = m.tan(delta_z)
#
#
#     FLatBrkDraM1 = FLatBrkDPassive +
#
#     return FLatBrkDraM1;

# This function return te drained lateral breakout resistance in accodance with DNVGL RP F114 Equation 4.27 and 4.28
def FLatBrkDraM2(z:float, D:float, gamma:float=d_gamma, V:float=d_Wpipe):

    gamma_z = np.interp(z,gamma[:,0],gamma[:,1])

    constant = V*(gamma_z*(D**2))

    if constant >= 0.5:
        Fp = gamma_z*(D**2)*(5 - (0.15*gamma_z*(D**2)/V))*((z/D)**1.25)
    else:
        Fp = 2*gamma_z*(D**2)*((z/D)**1.25)

    FLatBrkDraM2 = 0.6*V + Fp

    return FLatBrkDraM2;

# This function return the lateral displacement of pipeline in accorance with 4.29 of DNVGL RP F114
def FLatResUnd(z:float, D:float, V:float=d_Wpipe):

    FLatResUnd = (0.32 + 0.8*((z/D)**0.8))*V
    return FLatResUnd;

# This function return the lateral displacement of pipeline in accorance with 4.30, 4.31 and 4.32 of DNVGL RP F114
def FLatResDraM2(D:float, gamma:float=d_gamma, V:float=d_Wpipe):

    Dref = 508e-03
    Ap = 0.25*pi*(D**2)
    gamma_z = np.interp(z,gamma[:,0],gamma[:,1])


    T1 = 0.71*(V/(gamma_z*Ap))**0.12
    T2 = (Dref/D)**0.18

    FLatResDraM2_BE = T1*T2
    FLatResDraM2_LE = (0.05 + 0.70*(FLatResDraM2_BE/V))*V
    FLatResDraM2_HE = (0.18 + 1.15*(FLatResDraM2_BE/V))*V

    # FLatResDraM2 = [FLatResDraM2_LE, FLatResDraM2_BE, FLatResDraM2_HE]
    FLatResDraM2 = T1

    return FLatResDraM2;

# This function return the lateral displacement of pipeline in accorance with Table 4-4 and Table 4-5 of DNVGL RP F114
def DLat(z:float, D:float):

    DLatTri = np.array([
        np.array([
        (0.004 + 0.020*(z/D))*D,
        (0.020 + 0.250*(z/D))*D,
        (0.100 + 0.700*(z/D))*D,
        ]),
        np.array([
        0.60*D,
        1.50*D,
        2.80*D,
        ])
    ])

    return DLatTri;

# print(__name__)
# print(sys.argv)

# Import AppData
D = float(sys.argv[2])
Wins = float(sys.argv[3])
Whdt = float(sys.argv[4])
Wopt = float(sys.argv[5])
I = float(sys.argv[6])
T0 = float(sys.argv[7])
E = 2.07e+11

Depth = sys.argv[8]
gamma = sys.argv[9]
su = sys.argv[10]
su_re = sys.argv[11]
phi = sys.argv[12]
deltaPeak = sys.argv[13]
deltaRes = sys.argv[14]

Depth = Depth.split(',')
gamma = gamma.split(',')
su = su.split(',')
su_re = su_re.split(',')
phi = phi.split(',')
deltaPeak = deltaPeak.split(',')
deltaRes = deltaRes.split(',')

gamma = np.column_stack((Depth, gamma))
gamma = gamma.astype(np.float)

su = np.column_stack((Depth, su))
su = su.astype(np.float)

su_re = np.column_stack((Depth, su_re))
su_re = su_re.astype(np.float)

phi = np.column_stack((Depth, phi))
phi = phi.astype(np.float)

deltaPeak = np.column_stack((Depth, deltaPeak))
deltaPeak = deltaPeak.astype(np.float)

deltaRes = np.column_stack((Depth, deltaRes))
deltaRes = deltaRes.astype(np.float)

vMethod = 'UD2'

W = [Wins, Whdt, Wopt]
stages = ['Installation','Hydrostatic test', 'Operation']
alpha =1
gammaRate = 1
shansep_m = [0.70, 0.70, 0.70]

#Step 1: Determine pipeline embedment
#Step 1.1: Guess the inital embedment value
z= 1e-10
zIns = 0.2

# def klay(z:float, D:float, method:str, su:float=d_su, phi:float=d_phi, gamma:float=d_gamma, surface:float=d_surface, Woffset:float=d_Woffset, Wpipe:float=d_Wpipe, I:float=d_I, E:float=d_E, T0:float=d_T0 ):

#Step 1.2: Solving installation embedment
data = (D, vMethod, su_re, phi, gamma, d_surface, 0, W[0], I, E, T0)
zIns = fsolve(klay, z, args=data)

klay = QvAll(zIns, D, vMethod, su_re, phi, gamma, d_surface, 0)/W[0]

if klay < 1:
   data = (D, vMethod, su_re, phi, gamma, d_surface, W[0])
   zIns = fsolve(QvAll, z, args = data)

# Step 1.3: Solving hydrotest embedment
data = (D, vMethod, su, phi, gamma, d_surface, W[1])
zHdt = fsolve(QvAll, z, args = data)

# Step 1.4: Solving operating embedment
data = (D, vMethod, su, phi, gamma, d_surface, W[2])
zOpt = fsolve(QvAll, z, args = data)

# Step 1.5: Report
zCal = [zIns, zHdt, zOpt]
zUse = [zIns, max([zIns, zHdt]), max(zIns, zHdt, zOpt)]


print (sTitle, ' Version ', sVersion)
print (separate)
print ('Embedment analysis')
print ('Laying factor')
print (fFormat.format(float(klay), precision ))
print ('Caculated embedment (mm) Installation, Hydrostatic test, Operating')
print (fFormat.format(float(zCal[0]*1000), precision ),", ", fFormat.format(float(zCal[1]*1000), precision ),", ", fFormat.format(float(zCal[2]*1000), precision ))
print ('Used embedment (mm) Installation, Hydrostatic test, Operating')
print (fFormat.format(float(zUse[0]*1000), precision ),", ", fFormat.format(float(zUse[1]*1000), precision ),", ", fFormat.format(float(zUse[2]*1000), precision ))

#Step 2: Determine the axial friction coefficient
#Step 2.1: Determine OCR

SeffMax = klay*W[0]
OCR = [0 for i in range(len(stages))]

for i in range(len(stages)):

   if i == 0:
       if klay < 1.0:
           Seff = W[i]
       else:
           Seff = klay*W[i]
   else:
       Seff = W[i]

   if Seff > SeffMax:
       SeffMax = Seff

   OCR[i] = SeffMax/Seff


# print(deltaRes)

#Step 2.2: Undrained resistance
FAxeBrkUndrain = [0 for i in range(len(stages))]
FAxeResUndrain = [0 for i in range(len(stages))]
FAxeBrkDrain = [0 for i in range(len(stages))]
FAxeResDrain = [0 for i in range(len(stages))]

for i in range(len(stages)):

# z, D, alpha, su, gamma, OCR, m=0, gammaRate, V:float

    # print (zUse[i][0])

    # def FAxeBrkUnd(z:float, D:float, alpha:float=d_alpha, su:float=d_su, gamma:float=d_gamma, OCR:float=1, m:float=0.775, gammaRate:float=d_gammaRate, V:float=d_Wpipe)
    #
    FAxeBrkUndrain[i] = FAxeBrkUnd(zUse[i][0], D, alpha, su, gamma, OCR[i], shansep_m[i], gammaRate, W[i])
    # print(zUse[i][0], D, alpha, su, gamma, OCR[i], shansep_m[i], gammaRate, W[i])
    # print (zUse[i][0], ',', FAxeBrkUndrain[i])

    su_z = np.interp(z,su[:,0],su[:,1])
    su_re_z = np.interp(z,su_re[:,0],su_re[:,1])
    st = su_z/su_re_z
    FAxeResUndrain[i] = FAxeBrkUndrain[i]/st

    FAxeBrkDrain[i] = FAxeDra(zUse[i][0], D, deltaPeak, W[i])
    FAxeResDrain[i] = FAxeDra(zUse[i][0], D, deltaRes, W[i])

print (separate)
print ('Axial resistant analysis')
print ('Overconsolidation ratio (-) Installation, Hydrostatic test, Operating')
print (fFormat.format(float(OCR[0]), precision ),", ", fFormat.format(float(OCR[1]), precision ),", ", fFormat.format(float(OCR[2]), precision ))
print ('Undrained Axial Breakout Resistant (N/m) Installation, Hydrostatic test, Operating')
print (fFormat.format(float(FAxeBrkUndrain[0]), precision ),", ", fFormat.format(float(FAxeBrkUndrain[1]), precision ),", ", fFormat.format(float(FAxeBrkUndrain[2]), precision ))
print ('Undrained Axial Residual Resistant (N/m) Installation, Hydrostatic test, Operating')
print (fFormat.format(float(FAxeResUndrain[0]), precision ),", ", fFormat.format(float(FAxeResUndrain[1]), precision ),", ", fFormat.format(float(FAxeResUndrain[2]), precision ))
print ('Drained Axial Breakout Resistant (N/m) Installation, Hydrostatic test, Operating')
print (fFormat.format(float(FAxeBrkDrain[0]), precision ),", ", fFormat.format(float(FAxeBrkDrain[1]), precision ),", ", fFormat.format(float(FAxeBrkDrain[2]), precision ))
print ('Drained Axial Residual Resistant (N/m) Installation, Hydrostatic test, Operating')
print (fFormat.format(float(FAxeResDrain[0]), precision ),", ", fFormat.format(float(FAxeResDrain[1]), precision ),", ", fFormat.format(float(FAxeResDrain[2]), precision ))

#Step 3: Determine laterl friction coefficient
FLatBrkUndrainModel2 = [0 for i in range(len(stages))]
FLatResUndrain = [0 for i in range(len(stages))]
FLatBrkDrainModel2 = [0 for i in range(len(stages))]
FLatResDrain = [0 for i in range(len(stages))]

for i in range(len(stages)):

    FLatBrkUndrainModel2[i] = FLatBrkUndM2(zUse[i][0],D, su, gamma, W[i])
    FLatResUndrain[i] = FLatResUnd(zUse[i][0], D, W[i])
    FLatBrkDrainModel2[i] = FLatBrkDraM2(zUse[i][0], D, gamma, W[i])
    FLatResDrain[i] = FLatResDraM2(D, W[i])

print (separate)
print ('Lateral resistant analysis')
print('Latearal resistance calculation')
print ('Undrained lateral breakout resistant (Model 2) (N/m) Installation, Hydrostatic test, Operating')
print (fFormat.format(float(FLatBrkUndrainModel2[0]), precision ),", ", fFormat.format(float(FLatBrkUndrainModel2[1]), precision ),", ", fFormat.format(float(FLatBrkUndrainModel2[2]), precision ))
print ('Undrained lateral residual resistant (Model 2) (N/m) Installation, Hydrostatic test, Operating')
print (fFormat.format(float(FLatResUndrain[0]), precision ),", ", fFormat.format(float(FLatResUndrain[1]), precision ),", ", fFormat.format(float(FLatResUndrain[2]), precision ))
print ('Drained lateral breakout resistant (Model 2) (N/m) Installation, Hydrostatic test, Operating')
print (fFormat.format(float(FLatBrkDrainModel2[0]), precision ),", ", fFormat.format(float(FLatBrkDrainModel2[1]), precision ),", ", fFormat.format(float(FLatBrkDrainModel2[2]), precision ))
print ('Drained lateral residual resistant (Model 2) (N/m) Installation, Hydrostatic test, Operating')
print (fFormat.format(float(FLatResDrain[0]), precision ),", ", fFormat.format(float(FLatResDrain[1]), precision ),", ", fFormat.format(float(FLatResDrain[2]), precision ))


#
# print(FLatBrkUndrainModel1)
# print(FLatBrkUndrainModel2)


# klay = QvAll(zIns, D, vMethod, su_re, phi, gamma, surface, 0)/W[0]
# print (klay);

# def hello(a=1, b=1):
#     print ("hello and that's your sum:")
#     sum = a+b
#     print (sum)

# if __name__== "__main__":
#     hello(int(sys.argv[2]), int(sys.argv[3]))
