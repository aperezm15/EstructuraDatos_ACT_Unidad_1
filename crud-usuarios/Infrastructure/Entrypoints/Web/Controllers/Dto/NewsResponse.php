<?php
final class NewsResponse {
    public function __construct(
        private string $id,
        private string $categoria,
        private string $fecha,
        private string $pais,
        private string $departamento,
        private string $ciudad,
        private string $periodista,
        private string $programaEmite,
        private string $fechaEmision,
        private string $descripcion,
        private string $nivelPublico
    ) {}

    // Métodos getId(), getCategoria(), getFecha(), etc... (Sigue el patrón de UserResponse)
    public function getId(): string { return $this->id; }
    public function getCategoria(): string { return $this->categoria; }
    public function getDescripcion(): string { return $this->descripcion; }
    // ... agrega los demás getters para usarlos en las vistas
}