<style>
    .fi-body.fi-panel-portal {
        --portal-sidebar-active-bg: rgb(37 99 235);
        --portal-sidebar-active-bg-hover: rgb(29 78 216);
        --portal-sidebar-active-text: rgb(255 255 255);
        --portal-sidebar-hover-bg: rgb(239 246 255);
        --portal-sidebar-hover-text: rgb(30 64 175);
    }

    @media (max-width: 768px) {
        .fi-body.fi-panel-portal {
            --sidebar-width: 60vw;
        }

        .fi-body.fi-panel-portal .fi-topbar {
            z-index: 40;
        }

        .fi-body.fi-panel-portal .fi-topbar-open-sidebar-btn,
        .fi-body.fi-panel-portal .fi-topbar-close-sidebar-btn {
            position: relative;
            z-index: 50;
            border-radius: 9999px;
        }

        .fi-body.fi-panel-portal .fi-topbar-close-sidebar-btn {
            background-color: rgb(37 99 235);
            color: rgb(255 255 255);
        }

        .fi-body.fi-panel-portal .fi-main-sidebar.fi-sidebar {
            width: 60vw;
            max-width: 60vw;
        }

        .fi-body.fi-panel-portal .fi-sidebar-close-overlay {
            left: 60vw;
            width: 40vw;
        }
    }

    .fi-body.fi-panel-portal .fi-sidebar-item-button:hover,
    .fi-body.fi-panel-portal .fi-sidebar-item-button:focus-visible {
        background-color: var(--portal-sidebar-hover-bg) !important;
    }

    .fi-body.fi-panel-portal .fi-sidebar-item-button:hover .fi-sidebar-item-label,
    .fi-body.fi-panel-portal .fi-sidebar-item-button:focus-visible .fi-sidebar-item-label,
    .fi-body.fi-panel-portal .fi-sidebar-item-button:hover .fi-sidebar-item-icon,
    .fi-body.fi-panel-portal .fi-sidebar-item-button:focus-visible .fi-sidebar-item-icon {
        color: var(--portal-sidebar-hover-text) !important;
    }

    .fi-body.fi-panel-portal .fi-sidebar-item.fi-active > .fi-sidebar-item-button,
    .fi-body.fi-panel-portal .fi-sidebar-item.fi-active > .fi-sidebar-item-button:hover,
    .fi-body.fi-panel-portal .fi-sidebar-item.fi-active > .fi-sidebar-item-button:focus-visible {
        background-color: var(--portal-sidebar-active-bg) !important;
    }

    .fi-body.fi-panel-portal .fi-sidebar-item.fi-active > .fi-sidebar-item-button .fi-sidebar-item-label,
    .fi-body.fi-panel-portal .fi-sidebar-item.fi-active > .fi-sidebar-item-button .fi-sidebar-item-icon,
    .fi-body.fi-panel-portal .fi-sidebar-item.fi-active > .fi-sidebar-item-button:hover .fi-sidebar-item-label,
    .fi-body.fi-panel-portal .fi-sidebar-item.fi-active > .fi-sidebar-item-button:hover .fi-sidebar-item-icon,
    .fi-body.fi-panel-portal .fi-sidebar-item.fi-active > .fi-sidebar-item-button:focus-visible .fi-sidebar-item-label,
    .fi-body.fi-panel-portal .fi-sidebar-item.fi-active > .fi-sidebar-item-button:focus-visible .fi-sidebar-item-icon {
        color: var(--portal-sidebar-active-text) !important;
    }

    .fi-body.fi-panel-portal .fi-sidebar-item.fi-active > .fi-sidebar-item-button:hover {
        background-color: var(--portal-sidebar-active-bg-hover) !important;
    }
</style>
