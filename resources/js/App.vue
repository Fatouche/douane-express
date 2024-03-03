<template>

    <nav class="navbar navbar-expand-lg bg-body-tertiary navbar bg-dark border-bottom border-body" data-bs-theme="dark">
        <div class="container-fluid">
            <router-link class="navbar-brand" to="/services">Douane Express</router-link>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <router-link class="nav-link" to="/services">Services</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link class="nav-link" to="/orders">Bons de commande</router-link>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <router-view />
</template>