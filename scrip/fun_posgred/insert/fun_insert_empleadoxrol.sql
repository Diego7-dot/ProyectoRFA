-- Keren Morgado
-- Insercion de empleado x rol
-- SELECT fun_insert_empleado_rol('12441', 2);
-- SELECT * FROM empleado_rol;
-- SELECT * FROM empleado;
-- SELECT * FROM rol;

CREATE OR REPLACE FUNCTION fun_insert_empleado_rol(
    wdoc_emp_rol empleado_rol.doc_emp_rol%TYPE, -- Documento del empleado
    wcod_rol_emp empleado_rol.cod_rol_emp%TYPE  -- Código del rol
) RETURNS BOOLEAN AS
$$
DECLARE
    wcod_emp_rol empleado_rol.cod_emp_rol%TYPE;
BEGIN
    -- El autoincrementable
    SELECT MAX(cod_emp_rol) INTO wcod_emp_rol FROM empleado_rol;
	
    IF wcod_emp_rol IS NOT NULL THEN
        wcod_emp_rol := wcod_emp_rol + 1;
    ELSE
        wcod_emp_rol := 1;
    END IF;

    -- Inserción de la nueva relación empleado-rol
    INSERT INTO empleado_rol (cod_emp_rol, doc_emp_rol, cod_rol_emp)
    VALUES (wcod_emp_rol, wdoc_emp_rol, wcod_rol_emp);

    -- Verificación de la inserción
    IF FOUND THEN
        RAISE NOTICE 'Inserción exitosa: Empleado % con rol % ha sido agregado.', wdoc_emp_rol, wcod_rol_emp;
        RETURN TRUE;
    ELSE
        RAISE NOTICE 'Error: No se pudo insertar la relación empleado % con rol %.', wdoc_emp_rol, wcod_rol_emp;
        RETURN FALSE;
    END IF;
END;
$$ LANGUAGE plpgsql;
