DELIMITER $$

CREATE PROCEDURE sp_insert_usuario(
    IN wcorUsu VARCHAR(255),
    IN wnomUsu VARCHAR(255),
    IN wapeUsu VARCHAR(255),
    IN wfecNacUsu DATE,
    IN wgenUsu CHAR(1),
    IN wtelUsu VARCHAR(20),
    IN wdirUsu VARCHAR(255),
    IN wpsw VARCHAR(255),
    IN wnomciud INT
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION 
    BEGIN
        -- Captura de error
        ROLLBACK;
        SELECT 'Error al intentar insertar el usuario' AS mensaje;
    END;
    
    -- Iniciamos una transacción
    START TRANSACTION;
    
    -- Insertamos el usuario en la tabla
    INSERT INTO usuario (
        cor_usu, nom_usu, ape_usu, fec_nac_usu, gen_usu, tel_usu, dir_usu, psw, cod_ciu_usu
    ) VALUES (
        wcorUsu, wnomUsu, wapeUsu, wfecNacUsu, wgenUsu, wtelUsu, wdirUsu, wpsw, wnomciud
    );
    
    -- Confirmamos la transacción
    COMMIT;
    
    -- Mensaje de éxito
    SELECT CONCAT('El usuario ', wcorUsu, ' fue insertado correctamente.') AS mensaje;
END$$

DELIMITER ;
