CREATE PROCEDURE DELETACONTASUSUARIOS(
  PCODIGO INTEGER)
AS
BEGIN
  DELETE
  FROM CONTASUSUARIOS
  WHERE (CODIGO = :PCODIGO);
  SUSPEND;
END