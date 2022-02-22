CREATE PROCEDURE ALTERAUNIDADE(
  PCODIGO INTEGER,
  PID CHAR(2) CHARACTER SET ISO8859_1,
  PNOME VARCHAR(70) CHARACTER SET ISO8859_1,
  PDEPARTAMENTO VARCHAR(80) CHARACTER SET ISO8859_1)
AS
BEGIN
  UPDATE UNIDADE
  SET ID = :PID, NOME = :PNOME, DEPARTAMENTO = :PDEPARTAMENTO
  WHERE CODIGO = :PCODIGO;
  SUSPEND;
END