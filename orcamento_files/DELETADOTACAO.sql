CREATE PROCEDURE DELETADOTACAO(
  PCODIGO INTEGER)
AS
BEGIN
  DELETE
  FROM DOTACAO
  WHERE CODIGO = :PCODIGO;
  SUSPEND;
END
