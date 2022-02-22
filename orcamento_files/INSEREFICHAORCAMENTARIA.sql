CREATE PROCEDURE INSEREFICHAORCAMENTARIA(
  PCODIGO INTEGER,
  PCODIGOMOVIMENTO INTEGER,
  PCODIGODOTACAO INTEGER,
  PDATA DATE,
  PNEMPENHO INTEGER,
  PDESCRICAO VARCHAR(150) CHARACTER SET ISO8859_1,
  PDEBITO NUMERIC(18, 2),
  PCREDITO NUMERIC(18, 2),
  POBSERVACAO VARCHAR(150) CHARACTER SET ISO8859_1)
AS
BEGIN
  IF (PDEBITO <> 0) THEN
  BEGIN
    INSERT INTO FICHAORCAMENTARIA (CODIGO, CODIGOMOVIMENTO, CODIGODOTACAO, DATA,
    NEMPENHO, DESCRICAO, DEBITO, OBSERVACAO)
    VALUES (:PCODIGO, :PCODIGOMOVIMENTO, :PCODIGODOTACAO, :PDATA, :PNEMPENHO,
    :PDESCRICAO, :PDEBITO, :POBSERVACAO);
    SUSPEND;
  END
  ELSE
  BEGIN
    INSERT INTO FICHAORCAMENTARIA (CODIGO, CODIGOMOVIMENTO, CODIGODOTACAO, DATA,
    NEMPENHO, DESCRICAO, CREDITO, OBSERVACAO)
    VALUES (:PCODIGO, :PCODIGOMOVIMENTO, :PCODIGODOTACAO, :PDATA, :PNEMPENHO,
    :PDESCRICAO, :PCREDITO, :POBSERVACAO);
    SUSPEND;
  END
END
