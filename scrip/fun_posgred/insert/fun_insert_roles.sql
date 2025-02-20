-- Keren Morgado
-- Insercion de roles (Porque no?)
-- SELECT fun_insert_roles ('Administrador');
-- SELECT fun_insert_roles ('Consultor legal');
-- SELECT fun_insert_roles ('Psicologo');
-- SELECT fun_insert_roles ('Enfermero');
-- SELECT fun_insert_roles ('Asesor');
-- SELECT * FROM rol;

CREATE OR REPLACE FUNCTION fun_insert_roles(
    wnom_rol rol.nom_rol%TYPE
    ) RETURNS BOOLEAN AS
$$
DECLARE
    wcod_rol rol.cod_rol%TYPE;
BEGIN
    -- Para el autoincrementable de rol (Select con el cod??)
    SELECT MAX(cod_rol) INTO wcod_rol FROM rol;

    IF wcod_rol IS NOT NULL THEN
        wcod_rol := wcod_rol + 1;
    ELSE
        wcod_rol := 1;
    END IF;

    -- Inserción
    INSERT INTO rol (cod_rol, nom_rol)
    VALUES (wcod_rol, wnom_rol);

    -- Verificación
    IF FOUND THEN
        RAISE NOTICE 'Insertado exitosa del rol con codigo: % y nombre: %.', wcod_rol, wnom_rol;
        RETURN TRUE;
    ELSE
        RAISE NOTICE 'No se pudo insertar el rol, perdiste.';
        RETURN FALSE;
    END IF;
END;
$$ LANGUAGE plpgsql;
