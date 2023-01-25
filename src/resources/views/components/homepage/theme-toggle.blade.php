<button class="rounded-md focus:outline-none focus:shadow-outline-purple" @click="toggleTheme" aria-label="Toggle color mode">
    <template x-if="!dark">
        <i class="fa-solid fa-sun"></i>
    </template>
    <template x-if="dark">
        <i class="fa-duotone fa-moon"></i>
    </template>
</button>