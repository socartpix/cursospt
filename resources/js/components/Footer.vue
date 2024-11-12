// resources/js/components/Footer.vue
<template>
  <footer class="bg-gray-800 text-white">
    <div class="max-w-7xl mx-auto px-4 py-12">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div>
          <h3 class="text-lg font-bold mb-4">CursosOnline</h3>
          <p class="text-gray-400">
            Plataforma de aprendizaje en línea para todas las edades
          </p>
        </div>
        
        <div>
          <h3 class="text-lg font-bold mb-4">Enlaces Rápidos</h3>
          <ul class="space-y-2">
            <li>
              <router-link to="/cursos" class="text-gray-400 hover:text-white">
                Explorar Cursos
              </router-link>
            </li>
            <li>
              <router-link to="/register" class="text-gray-400 hover:text-white">
                Registrarse
              </router-link>
            </li>
            <li>
              <router-link to="/contact" class="text-gray-400 hover:text-white">
                Contacto
              </router-link>
            </li>
          </ul>
        </div>
        
        <div>
          <h3 class="text-lg font-bold mb-4">Contacto</h3>
          <ul class="space-y-2 text-gray-400">
            <li>Email: info@cursosonline.com</li>
            <li>Teléfono: (123) 456-7890</li>
          </ul>
        </div>
      </div>

      <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
        <p>&copy; {{ new Date().getFullYear() }} CursosOnline. Todos los derechos reservados.</p>
      </div>
    </div>
  </footer>
</template>