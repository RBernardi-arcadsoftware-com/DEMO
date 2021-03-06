     H
     H*------------------------------------------------------------------------
     H* Test procedures for 3 Pck
     H*------------------------------------------------------------------------
     D* Prototype for this module
     D/COPY SMK42M3_P
     D* Prototype for called procedures
     D/COPY SMK21M2_P
     D*
     D* Parameters interface for this module
     D SMK42M3         Pi
     D* 1=Min, 2=Max
     D e_Action                       1a
     D e_Pck1                        30p 9
     D e_Pck2                        30p 9
     D e_Pck3                        30p 9
     D e_PckRet                      30p 9
     D*--------
     D*
     C*
     C                   Select
     C*
     C*-- Min
     C                   When      e_Action = '1'
     C                   Eval      e_PckRet = Get_PckMin(
     C                             e_Pck1:e_Pck2:e_Pck3)
     C*-- Max
     C                   When      e_Action = '2'
     C                   Eval      e_PckRet = Get_PckMax(
     C                             e_Pck1:e_Pck2:e_Pck3)
     C*
     C                   Endsl
     C*
     C                   Eval      *INLR = '1'
     C                   Return
     C*---------------------------------------
