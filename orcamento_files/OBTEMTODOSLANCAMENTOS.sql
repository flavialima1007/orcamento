CREATE PROCEDURE OBTEMTODOSLANCAMENTOS(
  PCODIGOMOVIMENTO INTEGER,
  PCODIGOCONTA INTEGER,
  PCODIGOTIPOCONTA INTEGER,
  PGRUPO CHAR(6) CHARACTER SET ISO8859_1,
  PRECEITA CHAR(1) CHARACTER SET ISO8859_1,
  PDATADE DATE,
  PDATAATE DATE,
  PDESCRICAOO VARCHAR(150) CHARACTER SET ISO8859_1)
RETURNS(
  RCODIGO INTEGER,
  RCODIGOMOVIMENTO INTEGER,
  RCODIGOORCAMENTO INTEGER,
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
  RDESCRICAOCONTA VARCHAR(150) CHARACTER SET ISO8859_1,
  RTOTALDEBITO NUMERIC(18, 2),
  RDESCRICAOTIPOCONTA VARCHAR(150) CHARACTER SET ISO8859_1,
  RGRUPOD CHAR(6) CHARACTER SET ISO8859_1,
  RRECEITAD CHAR(1) CHARACTER SET ISO8859_1)
AS
BEGIN
  IF (PCODIGOCONTA IS NULL) THEN
  BEGIN
    FOR SELECT L.CODIGO, L.CODIGOMOVIMENTO, L.CODIGOORCAMENTO, L.CODIGOCONTA,
    L.GRUPO, L.RECEITA, L.DATA, L.NEMPENHO, L.DESCRICAO, L.DEBITO, L.CREDITO,
    L.SALDO, L.OBSERVACAO, L.ESTORNADO, L.USUARIO, L.DHMODIFICACAO,
    C.DESCRICAO, L.DEBITO, T.DESCRICAO
    FROM LANCAMENTOS L
    INNER JOIN CONTAS      C ON (L.CODIGOCONTA = C.CODIGO)
    INNER JOIN TIPOSCONTAS T ON (T.CODIGO = C.CODIGOTIPOCONTA)
    WHERE L.CODIGOMOVIMENTO = :PCODIGOMOVIMENTO AND L.OBSERVACAO LIKE '%' || :PDESCRICAOO || '%' AND (L.DATA >= :PDATADE AND L.DATA <= :PDATAATE)
    INTO :RCODIGO, :RCODIGOMOVIMENTO, :RCODIGOORCAMENTO, :RCODIGOCONTA, :RGRUPO,
    :RRECEITA, :RDATA, :RNEMPENHO, :RDESCRICAO, :RDEBITO, :RCREDITO, :RSALDO,
    :ROBSERVACAO, :RESTORNADO, :RUSUARIO, :RDHMODIFICACAO,
    :RDESCRICAOCONTA, :RTOTALDEBITO, :RDESCRICAOTIPOCONTA
    DO
    SUSPEND;
  END
  ELSE IF (PCODIGOCONTA = 0) THEN
  BEGIN
    FOR SELECT L.CODIGO, L.CODIGOMOVIMENTO, L.CODIGOORCAMENTO, L.CODIGOCONTA,
    L.GRUPO, L.RECEITA, L.DATA, L.NEMPENHO, L.DESCRICAO, L.DEBITO, L.CREDITO,
    L.SALDO, L.OBSERVACAO, L.ESTORNADO, L.USUARIO, L.DHMODIFICACAO,
    C.DESCRICAO, T.DESCRICAO
    FROM LANCAMENTOS L
    INNER JOIN CONTAS      C ON (L.CODIGOCONTA = C.CODIGO)
    INNER JOIN TIPOSCONTAS T ON (T.CODIGO = C.CODIGOTIPOCONTA)
    WHERE C.CODIGOTIPOCONTA = :PCODIGOTIPOCONTA
    AND L.CODIGOMOVIMENTO = :PCODIGOMOVIMENTO AND (L.DATA >= :PDATADE AND L.DATA <= :PDATAATE)
    INTO :RCODIGO, :RCODIGOMOVIMENTO, :RCODIGOORCAMENTO, :RCODIGOCONTA, :RGRUPO,
    :RRECEITA, :RDATA, :RNEMPENHO, :RDESCRICAO, :RDEBITO, :RCREDITO, :RSALDO,
    :ROBSERVACAO, :RESTORNADO, :RUSUARIO, :RDHMODIFICACAO,
    :RDESCRICAOCONTA, :RDESCRICAOTIPOCONTA
    DO
    SUSPEND;
  END
  ELSE
  BEGIN
    FOR SELECT L.CODIGO, L.CODIGOMOVIMENTO, L.CODIGOORCAMENTO, L.CODIGOCONTA,
    L.GRUPO, L.RECEITA, L.DATA, L.NEMPENHO, L.DESCRICAO,
    L.DEBITO, L.CREDITO, L.SALDO, L.OBSERVACAO, L.ESTORNADO, L.USUARIO,
    L.DHMODIFICACAO, C.DESCRICAO, T.DESCRICAO
    FROM LANCAMENTOS L
    INNER JOIN CONTAS      C ON (L.CODIGOCONTA = C.CODIGO)
    INNER JOIN TIPOSCONTAS T ON (T.CODIGO = C.CODIGOTIPOCONTA)
    WHERE CODIGOCONTA = :PCODIGOCONTA AND L.CODIGOMOVIMENTO = :PCODIGOMOVIMENTO
    AND L.GRUPO = :PGRUPO AND L.RECEITA = :PRECEITA AND (L.DATA >= :PDATADE AND L.DATA <= :PDATAATE)
    INTO :RCODIGO, :RCODIGOMOVIMENTO, :RCODIGOORCAMENTO, :RCODIGOCONTA, :RGRUPO,
    :RRECEITA, :RDATA, :RNEMPENHO, :RDESCRICAO, :RDEBITO, :RCREDITO, :RSALDO,
    :ROBSERVACAO, :RESTORNADO, :RUSUARIO, :RDHMODIFICACAO, :RDESCRICAOCONTA,
    :RDESCRICAOTIPOCONTA
    DO
    SUSPEND;
  END
END
