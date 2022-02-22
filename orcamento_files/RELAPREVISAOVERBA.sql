CREATE PROCEDURE RELAPREVISAOVERBA(
  PCODIGOMOVIMENTO INTEGER,
  PGRUPO CHAR(6) CHARACTER SET ISO8859_1,
  PRECEITA CHAR(1) CHARACTER SET ISO8859_1)
RETURNS(
  RDESCRICAO VARCHAR(150) CHARACTER SET ISO8859_1,
  RTOTALCREDITO NUMERIC(18, 2),
  RTOTALDEBITO NUMERIC(18, 2))
AS
BEGIN
  FOR SELECT C.DESCRICAO, (SUM(L.CREDITO)) AS TOTALCREDITO,
  (SUM(L.DEBITO)) AS TOTALDEBITO
  FROM LANCAMENTOS L
  INNER JOIN CONTAS      C ON (L.CODIGOCONTA     = C.CODIGO)
  INNER JOIN TIPOSCONTAS T ON (C.CODIGOTIPOCONTA = T.CODIGO)
  WHERE L.CODIGOMOVIMENTO = :PCODIGOMOVIMENTO
  AND (UPPER(C.DESCRICAO) LIKE 'VERBA%'OR UPPER(C.DESCRICAO) LIKE 'CONTROLE%')
  AND (L.GRUPO = :PGRUPO) AND (L.RECEITA = :PRECEITA)
  AND UPPER(T.DESCRICAO) LIKE 'PREVIS%'
  GROUP BY C.DESCRICAO
  INTO :RDESCRICAO, :RTOTALCREDITO, :RTOTALDEBITO
  DO SUSPEND;
END
