# Problema del Viajero

Un proyecto web para optimizar rutas de viaje, con generación de matrices aleatorias, cálculo de la mejor ruta y gestión de datos.

---

## 🚀 Características

- ✅ Generación de matrices aleatorias según rangos definidos por el usuario.  
- ✅ Algoritmo de optimización basado en búsqueda local aleatoria (con tiempo límite ajustable).  
- ✅ Cálculo de la mejor ruta entre ciudades (origen y destino seleccionables).  
- ✅ Interfaz intuitiva con visualización de costos y rutas destacadas.  
- ✅ Persistencia de datos en base de datos MySQL.

---

## 🔍 Método de Optimización

El sistema utiliza un algoritmo de búsqueda local aleatoria para encontrar la ruta más corta:

1. Genera permutaciones aleatorias de las ciudades intermedias.  
2. Evalúa el costo de cada ruta usando la matriz de distancias.  
3. Mantiene la mejor solución encontrada dentro de un límite de tiempo (ej: 5 segundos).

---

## 🛠️ Requisitos

- Servidor web (XAMPP, WAMP, etc.).  
- PHP 7.4+ y MySQL.  
- Base de datos configurada (ver `crear_base_datos.sql`).

---

## ▶️ Cómo Usar

1. Define los rangos para la matriz de costos (distancias entre ciudades).  
2. Selecciona origen y destino.  
3. El sistema calcula y muestra la ruta óptima con su costo total.  
4. Guarda las rutas para consultas futuras.
