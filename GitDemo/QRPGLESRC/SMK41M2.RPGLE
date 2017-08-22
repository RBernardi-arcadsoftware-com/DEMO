000100051123     H
000200051123     H*------------------------------------------------------------------------
000300051123     H* Test procedures for 2 int
000400051123     H*------------------------------------------------------------------------
000500051123     D* Prototype for called procedures
000600051123     D/COPY SMK21M1_P
000700051123     D
000800051123     D* Parameters
000900051123     D e_Action        s              1a
001000051123     D e_Int1          s             10i 0
001100051123     D e_Int2          s             10i 0
001200051123     D e_IntRet        s             10i 0
001300051123     D*--------
001400051123     D*
001500051123     C*
001600051123     C     *ENTRY        PLIST
001700051123     C                   Parm                    e_Action                          1=Min: 2=Max
001800051123     C                   Parm                    e_Int1
001900051123     C                   Parm                    e_Int2
002000051123     C                   Parm                    e_IntRet
002100051123     C*
002200051123     C                   Select
002300051123     C*
002400051123     C*-- Min
002500051123     C                   When      e_Action = '1'
002600051123     C                   Eval      e_IntRet = Get_IntMin(
002700051123     C                             e_Int1:e_Int2)
002800051123     C*-- Max
002900051123     C                   When      e_Action = '2'
003000051123     C                   Eval      e_IntRet = Get_IntMax(
003100051123     C                             e_Int1:e_Int2)
003200051123     C*
003300051123     C                   Endsl
003400051123     C*
003500051123     C                   Eval      *INLR = '1'
003600051123     C                   Return
003700051123     C*---------------------------------------