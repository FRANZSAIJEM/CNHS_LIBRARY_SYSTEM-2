<x-sidebar.overlay />

<aside
style="box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.286)"
    class="fixed inset-y-0 z-20 flex flex-col py-4 space-y-6 bg-orange-300 dark:bg-dark-eval-1"
    :class="{
        'translate-x-0 w-64': isSidebarOpen || isSidebarHovered,
        '-translate-x-full w-64 md:w-16 md:translate-x-0': !isSidebarOpen && !isSidebarHovered,
    }"
    style="transition-property: width, transform; transition-duration: 150ms;"
    x-on:mouseenter="handleSidebarHover(true)"
    x-on:mouseleave="handleSidebarHover(false)"
>
    <x-sidebar.header />

    <x-sidebar.content />

    <x-sidebar.footer />
</aside>
