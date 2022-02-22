CREATE PROCEDURE RELASDODOTACOES(
  PGRUPO CHAR(6) CHARACTER SET ISO8859_1,
  PCODIGOMOVIMENTO INTEGER)
RETURNS(
  RDOTACAO INTEGER,
  RITEM CHAR(6) CHARACTER SET ISO8859_1,
  RTOTALCREDITO NUMERIC(18, 2),
  RTOTALDEBITO NUMERIC(18, 2))
AS
BEGIN
  FOR SELECT D.DOTACAO, D.ITEM, (SUM(FO.CREDITO)) AS TOTALCREDITO,
  (SUM(FO.DEBITO)) AS TOTALDEBITO
  FROM DOTACAO D
  INNER JOIN FICHAORCAMENTARIA FO ON (FO.CODIGODOTACAO = D.CODIGO)
  WHERE D.GRUPO = :PGRUPO AND D.RECEITA <> 'S' AND FO.CODIGOMOVIMENTO = :PCODIGOMOVIMENTO
  GROUP BY D.DOTACAO, D.ITEM
  ORDER BY D.DOTACAO, D.ITEM
  INTO :RDOTACAO, :RITEM, :RTOTALCREDITO, :RTOTALDEBITO
  DO SUSPEND;
END
