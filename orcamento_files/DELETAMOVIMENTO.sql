CREATE PROCEDURE DELETAMOVIMENTO(
  PCODIGO INTEGER)
AS
BEGIN
  DELETE
  FROM MOVIMENTO
  WHERE CODIGO = :PCODIGO;
  SUSPEND;
END
