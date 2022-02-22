CREATE PROCEDURE RELAGASTOSUPLEMENTACAOREC(
  PCODIGOMOVIMENTO INTEGER,
  PGRUPO CHAR(6) CHARACTER SET ISO8859_1,
  PRECEITA CHAR(1) CHARACTER SET ISO8859_1)
RETURNS(
  RDESCRICAO VARCHAR(150) CHARACTER SET ISO8859_1,
  RTOTALCREDITO NUMERIC(18, 2),
  RTOTALDEBITO NUMERIC(18, 2))
AS
BEGIN
  FOR SELECT FO.DESCRICAO, (SUM(CREDITO)) AS TOTALCREDITO,
  (SUM(DEBITO)) AS TOTALDEBITO
  FROM FICHAORCAMENTARIA FO
  INNER JOIN DOTACAO D ON (FO.CODIGODOTACAO = D.CODIGO)
  WHERE FO.CODIGOMOVIMENTO = :PCODIGOMOVIMENTO
  AND UPPER(FO.DESCRICAO) LIKE 'SUPLEMENTA%'
  AND (UPPER(FO.DESCRICAO) NOT LIKE 'SUPLEMENTA% RECEITA%'
  AND UPPER(FO.DESCRICAO) NOT LIKE 'SUPLEMENTA% TRANSP%')
  AND UPPER(D.RECEITA) = UPPER(:PRECEITA) AND D.GRUPO = :PGRUPO
  GROUP BY FO.DESCRICAO
  ORDER BY FO.DESCRICAO
  INTO :RDESCRICAO, :RTOTALCREDITO, :RTOTALDEBITO
  DO SUSPEND;
END
