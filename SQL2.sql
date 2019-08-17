CREATE TABLE controlPedido(
    idPEdido int PRIMARY KEY AUTO_INCREMENT,
    idNota int,
    fecha date,
    noFac int,
    noClie int,
    cliente varchar(50),
    noVende int,
    vendedor varchar(50),
    preparacion char(1) DEFAULT 'N',
    despacho char(1) DEFAULT 'N',
    nulo char(1) DEFAULT 'N'
)
  ALTER TABLE controlPedido
ADD CONSTRAINT Unique_controlPedido
UNIQUE (idNota, noFac) 

CREATE TABLE pickingAlmacen(
    idPicking int PRIMARY KEY AUTO_INCREMENT,
    idNota int,
    piking varchar(30),
    revision varchar(30),
    embalaje varchar(30),
    falla varchar(30),
    fechaPreparacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT FK_Datos_pedido FOREIGN KEY (idNota) REFERENCES controlPedido(idNota)
)

CREATE table despachoAlmacen(
    idDespacho int PRIMARY KEY AUTO_INCREMENT,
    idNota int,
    cobrador varchar(30),
    fechaSalida date,
    horaSalida time,
    CONSTRAINT fk_idDespacho FOREIGN KEY(idNota) references controlPedido(idNota)
)

  CREATE TABLE nulos(
    idNulos int PRIMARY KEY AUTO_INCREMENT,
    idNota int,
    fechaNulo TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    motivo varchar(50),
    obs varchar(100),
    CONSTRAINT fk_idNulo FOREIGN KEY(idNota) references controlPedido(idNota)
    )
CREATE TABLE cliente(
    codCliente int primary key AUTO_INCREMENT,
    nombreCliente varchar(50),
    zona varchar(50)
)


create PROCEDURE controlPendientes()
  BEGIN
   SELECT fecha,idNota,noFac,noClie,cliente,preparacion,despacho from controlPedido where preparacion='N' AND nulo="N"
  END
    

CREATE PROCEDURE zonaPorCliente (IN cod int)
  BEGIN
   select zona from cliente where codCliente=@clienteS
  END
    

create procedure listaPedidoPicking()
  BEGIN
   select idNota,fecha,noFac from controlpedido WHERE preparacion='N'
  END
    


delimiter $
CREATE PROCEDURE insertarPicking (IN idNot int(11),IN piking varchar(30),IN revicion varchar(30),IN embalaje varchar(30),IN falla varchar(30))
begin
    insert into pickingAlmacen (idNota,piking,revision,embalaje,falla,fechaPreparacion) values (idNot,piking,revicion,embalaje,falla,null);
    UPDATE controlpedido set preparacion='S' where idNota=idNot; 
end $

CREATE PROCEDURE listarPickingActivo()
  BEGIN
   select idNota,noFac from controlPedido WHERE preparacion='S' and despacho='N'
  END
    


CREATE PROCEDURE reporteFecha (IN fechaInicial date,IN fechaFinal date)
BEGIN
 SELECT controlpedido.idNota,fecha,noFac,noClie,cliente,noVende,Vendedor,piking,revision,embalaje,falla,fechaPreparacion,cobrador,fechaSalida,horaSalida FROM controlpedido
      LEFT JOIN pickingalmacen ON pickingalmacen.idNota=controlpedido.idNota
      LEFT JOIN despachoalmacen ON despachoalmacen.idNota=controlpedido.idNota
      where fecha>=fechaInicial and fecha<=fechaFinal
UNION
SELECT controlpedido.idNota,fecha,noFac,noClie,cliente,noVende,Vendedor,piking,revision,embalaje,falla,fechaPreparacion,cobrador,fechaSalida,horaSalida FROM controlpedido
      RIGHT JOIN pickingalmacen ON pickingalmacen.idNota=controlpedido.idNota
      RIGHT JOIN despachoalmacen ON despachoalmacen.idNota=controlpedido.idNota
      where fecha>=fechaInicial and fecha<=fechaFinal
END


CREATE PROCEDURE reporteCod (IN cod int)
BEGIN
 SELECT controlpedido.idNota,fecha,noFac,noClie,cliente,noVende,Vendedor,piking,revision,embalaje,falla,fechaPreparacion,cobrador,fechaSalida,horaSalida FROM controlpedido
      LEFT JOIN pickingalmacen ON pickingalmacen.idNota=controlpedido.idNota
      LEFT JOIN despachoalmacen ON despachoalmacen.idNota=controlpedido.idNota
      where idNota=cod
UNION
SELECT controlpedido.idNota,fecha,noFac,noClie,cliente,noVende,Vendedor,piking,revision,embalaje,falla,fechaPreparacion,cobrador,fechaSalida,horaSalida FROM controlpedido
      RIGHT JOIN pickingalmacen ON pickingalmacen.idNota=controlpedido.idNota
      RIGHT JOIN despachoalmacen ON despachoalmacen.idNota=controlpedido.idNota
      where idNota=cod
END


CREATE PROCEDURE verificarNotaRepetida (IN nota int)
  BEGIN
    SELECT idNota from controlpedido where idNota=nota   
  END


DROP PROCEDURE verificarNotaRepetida
CREATE PROCEDURE verificarNotaRepetida (IN nota int,IN fac int)
    SELECT idNota,noFac from controlpedido where idNota=nota AND noFac=fac   

DROP PROCEDURE insertarNulo;

CREATE PROCEDURE insertarNulo(IN idNot int,IN motivo varchar(50),IN obs varchar(100))
insert into nulos (idNota,fechaNulo,motivo,obs) values (idNot,null,motivo,obs);


delimiter $
CREATE PROCEDURE insertarNulo (IN idNot int,IN motivo varchar(50),IN obs varchar(100))
begin
    insert into nulos (idNota,fechaNulo,motivo,obs) values (idNot,null,motivo,obs);
    UPDATE controlpedido set nulo='S' where idNota=idNot; 
end $