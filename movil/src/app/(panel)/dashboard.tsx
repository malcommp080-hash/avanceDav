import { useRouter } from "expo-router";
import { useState } from "react";
import { ActivityIndicator, RefreshControl, ScrollView, StyleSheet, Text, View } from "react-native";

import { Barras } from "@/components/barras";
import { Chips } from "@/components/chips";
import { Boton, Encabezado, Estadistica, TituloTarjeta } from "@/components/panel";
import { Tabla } from "@/components/tabla";
import { base, Color } from "@/constants/estilos";
import { useApi } from "@/hooks/use-api";
import { dinero } from "@/lib/api";

type Dashboard = {
  saludo: string;
  ventasHoy: { cantidad: number; monto: number };
  pedidosPendientes: { total: number; clientes: number; enLinea: number };
  inventarioBajo: { total: number; piezas: { producto: string; talla: string; stock: number }[] };
  totalClientes: number;
  totalVentas: number;
  totalPrendas: number;
  montoTotal: number;
  meses: { etiqueta: string; total: number }[];
  ultimosClientes: { id: number; nombre: string; telefono: string; direccion: string }[];
  ultimasVentas: { id: number; fecha: string; total: number; cliente: string }[];
};

const VISTAS = { barras: "Barras", linea: "Línea" };

export default function DashboardScreen() {
  const router = useRouter();
  const { datos: d, cargando, recargar } = useApi<Dashboard>("/dashboard");
  const [vista, setVista] = useState<keyof typeof VISTAS>("barras");

  return (
    <ScrollView
      contentContainerStyle={base.container}
      refreshControl={<RefreshControl refreshing={cargando && d !== null} onRefresh={recargar} tintColor={Color.primario} />}>
      <Encabezado titulo="Dashboard" sub={`${d?.saludo ?? "¡Hola!"} — Resumen general del sistema`} />

      {cargando && !d ? <ActivityIndicator color={Color.primario} style={{ marginVertical: 20 }} /> : null}

      {d ? (
        <>
          {/* RF006: lo que el administrador revisa primero cada día */}
          <Text style={styles.seccion}>HOY</Text>
          <View style={styles.grid}>
            <Estadistica
              color="azul"
              valor={dinero(d.ventasHoy.monto)}
              etiqueta={`Ventas del día · ${d.ventasHoy.cantidad} ${d.ventasHoy.cantidad === 1 ? "venta" : "ventas"}`}
              icono="cash-register"
              accion="Ver ventas"
              onPress={() => router.navigate("/ventas")}
            />
            <Estadistica
              color="amber"
              valor={String(d.pedidosPendientes.total)}
              etiqueta={`Pedidos pendientes · ${d.pedidosPendientes.clientes} de clientes, ${d.pedidosPendientes.enLinea} en línea`}
              icono="box"
              accion="Ver pedidos"
              onPress={() => router.navigate("/pedidos")}
            />
            <Estadistica
              color="red"
              valor={String(d.inventarioBajo.total)}
              etiqueta={`Inventario bajo · tallas con menos de 5 piezas`}
              icono="exclamation-triangle"
              accion="Ver inventario"
              onPress={() => router.navigate("/inventario")}
            />
          </View>

          {d.inventarioBajo.piezas.length ? (
            <Tabla
              icono="exclamation-triangle"
              colorIcono={Color.peligro}
              titulo="Por agotarse"
              columnas={["Producto", "Talla", "Piezas"]}
              filas={d.inventarioBajo.piezas.map((p) => [p.producto, p.talla, String(p.stock)])}
              onFila={() => router.navigate("/inventario")}
              vacio="Sin tallas bajas."
            />
          ) : null}

          <Text style={styles.seccion}>GENERAL</Text>
          <View style={styles.grid}>
            <Estadistica color="teal" valor={String(d.totalClientes)} etiqueta="Clientes Registrados" icono="users" accion="Ver clientes" onPress={() => router.navigate("/clientes")} />
            <Estadistica color="green" valor={String(d.totalVentas)} etiqueta="Ventas Totales" icono="chart-bar" accion="Ver ventas" onPress={() => router.navigate("/ventas")} />
            <Estadistica color="amber" valor={String(d.totalPrendas)} etiqueta="Prendas" icono="tshirt" accion="Ver prendas" onPress={() => router.navigate("/prendas")} />
            <Estadistica color="red" valor={dinero(d.montoTotal)} etiqueta="Monto Total Ventas" icono="dollar-sign" accion="Más info" onPress={() => router.navigate("/reportes")} />
          </View>

          <View style={base.card}>
            <View style={[base.row, { justifyContent: "space-between" }]}>
              <TituloTarjeta icono="chart-bar" texto="Ventas por Mes" />
              <View style={{ marginTop: -2 }}>
                <Chips opciones={VISTAS} valor={vista} onChange={setVista} />
              </View>
            </View>
            <Barras datos={d.meses.map((m) => ({ etiqueta: m.etiqueta, valor: m.total }))} linea={vista === "linea"} formato={dinero} />
          </View>

          <View style={base.card}>
            <TituloTarjeta icono="chart-pie" texto="Resumen" color={Color.turquesa} />
            <View style={styles.resumen}>
              <Dato titulo="Clientes" valor={d.totalClientes} color={Color.primario} />
              <Dato titulo="Ventas" valor={d.totalVentas} color={Color.turquesa} />
              <Dato titulo="Prendas" valor={d.totalPrendas} color={Color.amarillo} />
            </View>
          </View>

          <Tabla
            icono="user-clock"
            titulo="Últimos 5 Clientes"
            boton={<Boton texto="Ver todos" onPress={() => router.navigate("/clientes")} style={styles.btnSm} />}
            columnas={["ID", "Nombre", "Teléfono"]}
            filas={d.ultimosClientes.map((c) => [String(c.id), c.nombre, c.telefono])}
            vacio="Sin clientes."
          />

          <Tabla
            icono="receipt"
            colorIcono={Color.turquesa}
            titulo="Últimas 5 Ventas"
            boton={<Boton texto="Ver todas" tipo="success" onPress={() => router.navigate("/ventas")} style={styles.btnSm} />}
            columnas={["ID", "Cliente", "Total"]}
            filas={d.ultimasVentas.map((v) => [`#${v.id}`, v.cliente, dinero(v.total)])}
            onFila={(i) => router.push(`/venta/${d.ultimasVentas[i].id}`)}
            vacio="Sin ventas."
          />
        </>
      ) : null}
    </ScrollView>
  );
}

function Dato({ titulo, valor, color }: { titulo: string; valor: number; color: string }) {
  return (
    <View style={{ flex: 1, alignItems: "center" }}>
      <Text style={{ fontSize: 10, color: Color.textoSuave }}>{titulo}</Text>
      <Text style={{ fontSize: 20, fontWeight: "800", color }}>{valor}</Text>
    </View>
  );
}

const styles = StyleSheet.create({
  seccion: {
    color: Color.textoSuave,
    fontSize: 11,
    fontWeight: "700",
    letterSpacing: 1,
    marginBottom: 8,
  },
  grid: {
    flexDirection: "row",
    flexWrap: "wrap",
    gap: 12,
    marginBottom: 16,
  },
  resumen: {
    flexDirection: "row",
  },
  btnSm: {
    height: 30,
    marginTop: 0,
    paddingHorizontal: 11,
  },
});
