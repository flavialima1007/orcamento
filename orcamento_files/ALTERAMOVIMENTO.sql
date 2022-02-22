CREATE PROCEDURE ALTERAMOVIMENTO(
  PCODIGO INTEGER,
  PANO INTEGER,
  PCONCLUIDO CHAR(1) CHARACTER SET ISO8859_1,
  PATIVO CHAR(1) CHARACTER SET ISO8859_1)
AS
BEGIN
  IF (PATIVO = 'S') THEN
  BEGIN
    IF (NOT EXISTS(SELECT CODIGO FROM MOVIMENTO WHERE ANO = :PANO
    AND CODIGO <> :PCODIGO)) THEN
    BEGIN
      UPDATE MOVIMENTO
      SET ATIVO = '';
      UPDATE MOVIMENTO
      SET CONCLUIDO = :PCONCLUIDO, ATIVO = :PATIVO
      WHERE CODIGO = :PCODIGO;
      SUSPEND;
    END
    ELSE
      EXCEPTION REGISTROJAEXISTE;
  END
  ELSE
  BEGIN
    IF (NOT EXISTS(SELECT CODIGO FROM MOVIMENTO WHERE ANO = :PANO
    AND CODIGO <> :PCODIGO)) THEN
    BEGIN
      UPDATE MOVIMENTO
      SET CONCLUIDO = :PCONCLUIDO, ATIVO = :PATIVO
      WHERE CODIGO = :PCODIGO;
      SUSPEND;
    END
    ELSE
      EXCEPTION REGISTROJAEXISTE;
  END
END