CREATE PROCEDURE OBTEMDESCRIOBSERVACOES(
  PCODIGOTIPOCONTA INTEGER,
  PCODIGO INTEGER)
RETURNS(
  RCODIGO INTEGER,
  RCODIGOTIPOCONTA INTEGER,
  RID CHAR(1) CHARACTER SET ISO8859_1,
  RDESCRICAO VARCHAR(150) CHARACTER SET ISO8859_1,
  RUSUARIO CHAR(10) CHARACTER SET ISO8859_1,
  RDHMODIFICACAO TIMESTAMP,
  RDESCRICAOTIPOCONTA VARCHAR(150) CHARACTER SET ISO8859_1)
AS
BEGIN
  IF (PCODIGOTIPOCONTA IS NULL) THEN
  BEGIN
    FOR SELECT D.CODIGO, D.CODIGOTIPOCONTA, D.ID, D.DESCRICAO, D.USUARIO,
    D.DHMODIFICACAO, T.DESCRICAO
    FROM DESCRIOBSERVACOES D
    INNER JOIN TIPOSCONTAS T ON (D.CODIGOTIPOCONTA = T.CODIGO)
    WHERE D.CODIGO = :PCODIGO
    INTO :RCODIGO, :RCODIGOTIPOCONTA, :RID, :RDESCRICAO, :RUSUARIO,
    :RDHMODIFICACAO, :RDESCRICAOTIPOCONTA
    DO
    SUSPEND;
  END
  ELSE
  BEGIN
    FOR SELECT D.CODIGO, D.CODIGOTIPOCONTA, D.ID, D.DESCRICAO, D.USUARIO,
    D.DHMODIFICACAO, T.DESCRICAO
    FROM DESCRIOBSERVACOES D
    INNER JOIN TIPOSCONTAS T ON (D.CODIGOTIPOCONTA = T.CODIGO)
    WHERE D.CODIGOTIPOCONTA = :PCODIGOTIPOCONTA AND D.CODIGO = :PCODIGO
    INTO :RCODIGO, :RCODIGOTIPOCONTA, :RID, :RDESCRICAO, :RUSUARIO,
    :RDHMODIFICACAO, :RDESCRICAOTIPOCONTA
    DO
    SUSPEND;
  END
END
