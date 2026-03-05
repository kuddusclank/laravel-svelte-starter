<script lang="ts">
    import type { Snippet } from 'svelte';

    let { href, children, class: className = '' }: { href: string; children: Snippet; class?: string } = $props();
</script>

<a {href} class="preset-link text-sm {className}">
    {@render children()}
</a>
