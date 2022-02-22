CREATE PROCEDURE OBTEMLANCAMENTOSCODIGO(
  PCODIGO INTEGER,
  PCODIGOORCAMENTO INTEGER)
RETURNS(
  RCODIGO INTEGER,
  RCODIGOMOVIMENTO INTEGER,
  RCODIGOORCAMENTO INTEGER,
  RCODIGOTIPOCONTA INTEGER,
  RCODIGOCONTA INTEGER,
  RGRUPO CHAR(6) CHARACTER SET ISO8859_1,
  RRECEITA CHAR(1) CHARACTER SET ISO8859_1,
  RDATA DATE,
  RNEMPENHO INTEGER,
  RDESCRICAO VARCHAR(150) CHARACTER SET ISO8859_1,
  RDEBITO NUMERIC(18, 2),
  RCREDITO NUMERIC(18, 2),
  RSALDO NUMERIC(18, 2),
  ROBSERVACAO VARCHAR(150) CHARACTER SET ISO8859_1,
  RESTORNADO CHAR(1) CHARACTER SET ISO8859_1,
  RUSUARIO CHAR(10) CHARACTER SET ISO8859_1,
  RDHMODIFICACAO TIMESTAMP,
  RANOMOVIMENTO INTEGER,
  RDESCRICAOCONTA VARCHAR(150) CHARACTER SET ISO8859_1,
  RNOMEAREA VARCHAR(40) CHARACTER SET ISO8859_1,
  RDESCRICAOTIPOCONTA VARCHAR(150) CHARACTER SET ISO8859_1)
AS
BEGIN
  IF (:PCODIGO <> 0) THEN
  BEGIN
    FOR SELECT L.CODIGO, L.CODIGOMOVIMENTO, L.CODIGOORCAMENTO, T.CODIGO,
    L.CODIGOCONTA, L.GRUPO, L.RECEITA, L.DATA, L.NEMPENHO, L.DESCRICAO,
    L.DEBITO, L.CREDITO, L.SALDO, L.OBSERVACAO, L.ESTORNADO, L.USUARIO,
    L.DHMODIFICACAO, M.ANO, C.DESCRICAO, A.NOME, T.DESCRICAO
    FROM LANCAMENTOS L
    INNER JOIN MOVIMENTO   M ON (L.CODIGOMOVIMENTO = M.CODIGO)
    INNER JOIN CONTAS      C ON (L.CODIGOCONTA = C.CODIGO)
    LEFT  JOIN AREAS       A ON (C.CODIGOAREA = A.CODIGO)
    INNER JOIN TIPOSCONTAS T ON (C.CODIGOTIPOCONTA = T.CODIGO)
    WHERE L.CODIGO = :PCODIGO
    INTO :RCODIGO, :RCODIGOMOVIMENTO, :RCODIGOORCAMENTO, :RCODIGOTIPOCONTA,
    :RCODIGOCONTA, :RGRUPO, :RRECEITA, :RDATA, :RNEMPENHO, :RDESCRICAO,
    :RDEBITO, :RCREDITO, :RSALDO, :ROBSERVACAO, :RESTORNADO, :RUSUARIO,
    :RDHMODIFICACAO, :RANOMOVIMENTO, :RDESCRICAOCONTA, :RNOMEAREA,
    :RDESCRICAOTIPOCONTA
    DO
    SUSPEND;
  END
  ELSE
  BEGIN
    FOR SELECT L.CODIGO, L.CODIGOMOVIMENTO, L.CODIGOORCAMENTO, T.CODIGO,
    L.CODIGOCONTA, L.GRUPO, L.RECEITA, L.DATA, L.NEMPENHO, L.DESCRICAO,
    L.DEBITO, L.CREDITO, L.SALDO, L.OBSERVACAO, L.ESTORNADO, L.USUARIO,
    L.DHMODIFICACAO, M.ANO, C.DESCRICAO, A.NOME, T.DESCRICAO
    FROM LANCAMENTOS L
    INNER JOIN MOVIMENTO   M ON (L.CODIGOMOVIMENTO = M.CODIGO)
    INNER JOIN CONTAS      C ON (L.CODIGOCONTA = C.CODIGO)
    LEFT  JOIN AREAS       A ON (C.CODIGOAREA = A.CODIGO)
    INNER JOIN TIPOSCONTAS T ON (C.CODIGOTIPOCONTA = T.CODIGO)
    WHERE L.CODIGOORCAMENTO = :PCODIGOORCAMENTO
    INTO :RCODIGO, :RCODIGOMOVIMENTO, :RCODIGOORCAMENTO, :RCODIGOTIPOCONTA,
    :RCODIGOCONTA, :RGRUPO, :RRECEITA, :RDATA, :RNEMPENHO, :RDESCRICAO,
    :RDEBITO, :RCREDITO, :RSALDO, :ROBSERVACAO, :RESTORNADO, :RUSUARIO,
    :RDHMODIFICACAO, :RANOMOVIMENTO, :RDESCRICAOCONTA, :RNOMEAREA,
    :RDESCRICAOTIPOCONTA
    DO
    SUSPEND;
  END
END
