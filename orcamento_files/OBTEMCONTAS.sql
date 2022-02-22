CREATE PROCEDURE OBTEMCONTAS(
  PCODIGOTIPOCONTA INTEGER,
  PCODIGO INTEGER)
RETURNS(
  RCODIGO INTEGER,
  RCODIGOTIPOCONTA INTEGER,
  RCODIGOAREA INTEGER,
  RDESCRICAO VARCHAR(150) CHARACTER SET ISO8859_1,
  REMAIL VARCHAR(40) CHARACTER SET ISO8859_1,
  RNUMERO VARCHAR(10) CHARACTER SET ISO8859_1,
  RATIVO CHAR(1) CHARACTER SET ISO8859_1,
  RUSUARIO CHAR(10) CHARACTER SET ISO8859_1,
  RDHMODIFICACAO TIMESTAMP,
  RDESCRICAOTIPOCONTA VARCHAR(150) CHARACTER SET ISO8859_1,
  RNOMEAREA VARCHAR(40) CHARACTER SET ISO8859_1)
AS
BEGIN
  FOR SELECT C.CODIGO, C.CODIGOTIPOCONTA, C.CODIGOAREA, C.DESCRICAO, C.EMAIL,
  C.NUMERO, C.ATIVO, C.USUARIO, C.DHMODIFICACAO, T.DESCRICAO, A.NOME
  FROM CONTAS C
  INNER JOIN TIPOSCONTAS T ON (C.CODIGOTIPOCONTA = T.CODIGO)
  LEFT  JOIN AREAS       A ON (C.CODIGOAREA = A.CODIGO)
  WHERE C.CODIGO = :PCODIGO
  INTO :RCODIGO, :RCODIGOTIPOCONTA, :RCODIGOAREA, :RDESCRICAO, :REMAIL,
  :RNUMERO, :RATIVO, :RUSUARIO, :RDHMODIFICACAO, :RDESCRICAOTIPOCONTA,
  :RNOMEAREA
  DO
  SUSPEND;
END