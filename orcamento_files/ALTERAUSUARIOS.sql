CREATE PROCEDURE ALTERAUSUARIOS(
  PCODIGO INTEGER,
  PLOGIN VARCHAR(20) CHARACTER SET ISO8859_1,
  PSENHA CHAR(10) CHARACTER SET ISO8859_1,
  PDESCRICAO VARCHAR(150) CHARACTER SET ISO8859_1)
AS
BEGIN
  UPDATE USUARIOS
  SET LOGIN = :PLOGIN, SENHA = :PSENHA, DESCRICAO = :PDESCRICAO
  WHERE CODIGO = :PCODIGO;
  SUSPEND;
END
