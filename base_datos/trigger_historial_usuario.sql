-- KM = Trigger para llevar las modificaciones de perfil, si puede que haya uno para cuando elimina
DELIMITER $$

CREATE TRIGGER trg_edicion_usuario
BEFORE UPDATE ON usuario
FOR EACH ROW
BEGIN
    -- KM = Nombre o alias
    IF NEW.nomUsu <> OLD.nomUsu THEN
        INSERT INTO historial_ediciones_usuario (corUsuEdi, campoEditado, valorAnterior, valorNuevo)
        VALUES (OLD.corUsu, 'nomUsu', OLD.nomUsu, NEW.nomUsu);
    END IF;

    -- KM = Fecha de nacimiento
    IF NEW.fecNacUsu <> OLD.fecNacUsu THEN
        INSERT INTO historial_ediciones_usuario (corUsuEdi, campoEditado, valorAnterior, valorNuevo)
        VALUES (OLD.corUsu, 'fecNacUsu', OLD.fecNacUsu, NEW.fecNacUsu);
    END IF;

    -- KM = Genero
    IF NEW.genUsu <> OLD.genUsu THEN
        INSERT INTO historial_ediciones_usuario (corUsuEdi, campoEditado, valorAnterior, valorNuevo)
        VALUES (OLD.corUsu, 'genUsu', OLD.genUsu, NEW.genUsu);
    END IF;

    -- KM = Foto de perfil
    IF NEW.fotoPerfil <> OLD.fotoPerfil THEN
        INSERT INTO historial_ediciones_usuario (corUsuEdi, campoEditado, valorAnterior, valorNuevo)
        VALUES (OLD.corUsu, 'fotoPerfil', OLD.fotoPerfil, NEW.fotoPerfil);
    END IF;

    -- KM = Contraseña
    IF NEW.psw <> OLD.psw THEN
        INSERT INTO historial_ediciones_usuario (corUsuEdi, campoEditado, valorAnterior, valorNuevo)
        VALUES (OLD.corUsu, 'psw', OLD.psw, NEW.psw);
    END IF;
END$$

DELIMITER ;
