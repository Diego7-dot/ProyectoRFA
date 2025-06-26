-- KM = Trigger para registrar cambios en el perfil de personas de apoyo
DELIMITER $$

CREATE TRIGGER trg_edicion_persona_apoyo
BEFORE UPDATE ON personaApoyo
FOR EACH ROW
BEGIN
    -- KM = Nombre
    IF NEW.nomApo <> OLD.nomApo THEN
        INSERT INTO historial_ediciones_apoyo (docApoEdi, campoEditado, valorAnterior, valorNuevo)
        VALUES (OLD.docApo, 'nomApo', OLD.nomApo, NEW.nomApo);
    END IF;

    -- KM = Apellido
    IF NEW.apeApo <> OLD.apeApo THEN
        INSERT INTO historial_ediciones_apoyo (docApoEdi, campoEditado, valorAnterior, valorNuevo)
        VALUES (OLD.docApo, 'apeApo', OLD.apeApo, NEW.apeApo);
    END IF;

    -- KM = Fecha de nacimiento
    IF NEW.fecNacApo <> OLD.fecNacApo THEN
        INSERT INTO historial_ediciones_apoyo (docApoEdi, campoEditado, valorAnterior, valorNuevo)
        VALUES (OLD.docApo, 'fecNacApo', OLD.fecNacApo, NEW.fecNacApo);
    END IF;

    -- KM = Genero
    IF NEW.genApo <> OLD.genApo THEN
        INSERT INTO historial_ediciones_apoyo (docApoEdi, campoEditado, valorAnterior, valorNuevo)
        VALUES (OLD.docApo, 'genApo', OLD.genApo, NEW.genApo);
    END IF;

    -- KM = Telefono
    IF NEW.telApo <> OLD.telApo THEN
        INSERT INTO historial_ediciones_apoyo (docApoEdi, campoEditado, valorAnterior, valorNuevo)
        VALUES (OLD.docApo, 'telApo', OLD.telApo, NEW.telApo);
    END IF;

    -- KM = Direccion
    IF NEW.dirApo <> OLD.dirApo THEN
        INSERT INTO historial_ediciones_apoyo (docApoEdi, campoEditado, valorAnterior, valorNuevo)
        VALUES (OLD.docApo, 'dirApo', OLD.dirApo, NEW.dirApo);
    END IF;

    -- KM = Especialidad
    IF NEW.espProApo <> OLD.espProApo THEN
        INSERT INTO historial_ediciones_apoyo (docApoEdi, campoEditado, valorAnterior, valorNuevo)
        VALUES (OLD.docApo, 'espProApo', OLD.espProApo, NEW.espProApo);
    END IF;

    -- KM = Contraseña
    IF NEW.pswApo <> OLD.pswApo THEN
        INSERT INTO historial_ediciones_apoyo (docApoEdi, campoEditado, valorAnterior, valorNuevo)
        VALUES (OLD.docApo, 'pswApo', OLD.pswApo, NEW.pswApo);
    END IF;

    -- KM = Foto de perfil
    IF NEW.fotoPerfil <> OLD.fotoPerfil THEN
        INSERT INTO historial_ediciones_apoyo (docApoEdi, campoEditado, valorAnterior, valorNuevo)
        VALUES (OLD.docApo, 'fotoPerfil', OLD.fotoPerfil, NEW.fotoPerfil);
    END IF;
END$$

DELIMITER ;
