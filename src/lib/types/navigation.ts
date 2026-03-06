import type { Component } from 'svelte';

export interface NavItem {
    title: string;
    href: string;
    icon?: Component;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}
