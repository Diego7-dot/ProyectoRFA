-- Keren Morgado
-- Insercion de ciudades (Porque no?)
-- SELECT fun_insert_ciudades ('Bogotá');
-- SELECT fun_insert_ciudades ('Medellin');
-- SELECT fun_insert_ciudades ('Cali');
-- SELECT fun_insert_ciudades ('Bucaramanga');
-- SELECT fun_insert_ciudades ('Cartagena');
-- SELECT*FROM ciudad;

CREATE OR REPLACE FUNCTION fun_insert_ciudades(
    wnom_ciu ciudad.nom_ciu%TYPE
    ) RETURNS BOOLEAN AS
$$
DECLARE
    wcod_ciu ciudad.cod_ciu%TYPE;
BEGIN
    -- Autoincrementable 
    SELECT MAX(cod_ciu) INTO wcod_ciu FROM ciudad;
  
    IF wcod_ciu IS NOT NULL THEN
        wcod_ciu := wcod_ciu + 1;
    ELSE
        wcod_ciu := 1;
    END IF;

    -- Inserción 
    INSERT INTO ciudad (cod_ciu, nom_ciu)
    VALUES (wcod_ciu, wnom_ciu);

    -- Verifica si la inserción
    IF FOUND THEN
        RAISE NOTICE 'Inserción exitosa: Codigo % con nombre % ha sido agregado.', wcod_ciu, wnom_ciu;
        RETURN TRUE;
    ELSE
        RAISE NOTICE 'Error: No se pudo insertar el codigo % con nombre %.', wcod_ciu, wnom_ciu;
        RETURN FALSE;
    END IF;
END;
$$ LANGUAGE plpgsql;
