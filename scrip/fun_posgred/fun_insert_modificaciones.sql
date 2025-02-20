-- Keren Morgado
-- Insercion de historial de modificaciones (caso y reporte)

-- Caso (Nuevo)

CREATE OR REPLACE FUNCTION fun_insert_his_cas_insert()
RETURNS TRIGGER AS
$$
BEGIN
    INSERT INTO historial_modificaciones (cod_rep_his_mod, cod_cas_his_mod, doc_emp_his_mod, des_his_mod, fec_his_mod)
    VALUES (NEW.cod_rep, NEW.cod_cas, NEW.doc_emp, 'Inserción en el reporte', NOW());
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_caso_insert
AFTER INSERT ON caso
FOR EACH ROW
EXECUTE FUNCTION fun_insert_his_cas_insert();

-- Caso (Actualizar)

CREATE OR REPLACE FUNCTION fun_insert_his_cas_update()
RETURNS TRIGGER AS
$$
BEGIN
    INSERT INTO historial_modificaciones (cod_rep_his_mod, cod_cas_his_mod, doc_emp_his_mod, des_his_mod, fec_his_mod)
    VALUES (NEW.cod_rep, NEW.cod_cas, NEW.doc_emp, 'Modificación en un caso', NOW());
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_caso_update
AFTER UPDATE ON caso
FOR EACH ROW
EXECUTE FUNCTION fun_insert_his_cas_update();

-- Caso (Eliminar)

CREATE OR REPLACE FUNCTION fun_insert_his_cas_delete()
RETURNS TRIGGER AS
$$
BEGIN
    INSERT INTO historial_modificaciones (cod_rep_his_mod, cod_cas_his_mod, doc_emp_his_mod, des_his_mod, fec_his_mod)
    VALUES (OLD.cod_rep, OLD.cod_cas, OLD.doc_emp, 'Eliminación de un caso', NOW());
    RETURN OLD;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_caso_delete
AFTER DELETE ON caso
FOR EACH ROW
EXECUTE FUNCTION fun_insert_his_cas_delete();

-- Reporte (Nuevo)

CREATE OR REPLACE FUNCTION fun_insert_his_rep_insert()
RETURNS TRIGGER AS
$$
BEGIN
    INSERT INTO historial_modificaciones (cod_rep_his_mod, cod_cas_his_mod, doc_emp_his_mod, des_his_mod, fec_his_mod)
    VALUES (NEW.cod_rep, NEW.cod_cas, NEW.doc_emp, 'Inserción en el reporte', NOW());
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_reporte_insert
AFTER INSERT ON reporte
FOR EACH ROW
EXECUTE FUNCTION fun_insert_his_rep_insert();

-- Reporte (Actualizar)

CREATE OR REPLACE FUNCTION fun_insert_his_rep_update()
RETURNS TRIGGER AS
$$
BEGIN
    INSERT INTO historial_modificaciones (cod_rep_his_mod, cod_cas_his_mod, doc_emp_his_mod, des_his_mod, fec_his_mod)
    VALUES (NEW.cod_rep, NEW.cod_cas, NEW.doc_emp, 'Modificación en el reporte', NOW());
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_reporte_update
AFTER UPDATE ON reporte
FOR EACH ROW
EXECUTE FUNCTION fun_insert_his_rep_update();

-- Reporte (Eliminacion)

CREATE OR REPLACE FUNCTION fun_insert_his_rep_delete()
RETURNS TRIGGER AS
$$
BEGIN
    INSERT INTO historial_modificaciones (cod_rep_his_mod, cod_cas_his_mod, doc_emp_his_mod, des_his_mod, fec_his_mod)
    VALUES (OLD.cod_rep, OLD.cod_cas, OLD.doc_emp, 'Eliminación de un reporte', NOW());
    RETURN OLD;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_reporte_delete
AFTER DELETE ON reporte
FOR EACH ROW
EXECUTE FUNCTION fun_insert_his_rep_delete();
