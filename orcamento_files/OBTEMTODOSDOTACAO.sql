CREATE PROCEDURE OBTEMTODOSDOTACAO
RETURNS(
  RCODIGO INTEGER,
  RDOTACAO INTEGER,
  RGRUPO CHAR(6) CHARACTER SET ISO8859_1,
  RDESCRICAOGRUPO VARCHAR(150) CHARACTER SET ISO8859_1,
  RRECEITA CHAR(1) CHARACTER SET ISO8859_1,
  RITEM CHAR(6) CHARACTER SET ISO8859_1,
  RDESCRICAOITEM VARCHAR(150) CHARACTER SET ISO8859_1,
  RUSUARIO CHAR(10) CHARACTER SET ISO8859_1,
  RDHMODIFICACAO TIMESTAMP)
AS
BEGIN
  FOR SELECT D.CODIGO, D.DOTACAO, D.GRUPO, D.DESCRICAOGRUPO, D.RECEITA, D.ITEM,
  D.DESCRICAOITEM, D.USUARIO, D.DHMODIFICACAO
  FROM DOTACAO D
  INTO :RCODIGO, :RDOTACAO, :RGRUPO, :RDESCRICAOGRUPO, :RRECEITA, :RITEM,
  :RDESCRICAOITEM, :RUSUARIO, :RDHMODIFICACAO
  DO
  SUSPEND;
END
