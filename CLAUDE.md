## graphify

This project has a knowledge graph at graphify-out/ with god nodes, community structure, and cross-file relationships.

The CLI is **not on PATH** on this machine. Call it by its full path:
`"C:\Users\Tenstrings Music Ins\.local\bin\graphify.exe"` — e.g.
`& "C:\Users\Tenstrings Music Ins\.local\bin\graphify.exe" query "<question>"`.
Running `uv tool update-shell` once would put a plain `graphify` on PATH and make
the short commands below work as written.

Build artifacts are excluded via `.graphifyignore` (minified Filament/Vite bundles
under `public/js/` were 60% of the graph's nodes before that file existed). Leave
it in place, or the graph fills up with names like `_a()` and `Bc()`.

Rules:
- For codebase questions, first run `graphify query "<question>"` when graphify-out/graph.json exists. Use `graphify path "<A>" "<B>"` for relationships and `graphify explain "<concept>"` for focused concepts. These return a scoped subgraph, usually much smaller than GRAPH_REPORT.md or raw grep output.
- If graphify-out/wiki/index.md exists, use it for broad navigation instead of raw source browsing.
- Read graphify-out/GRAPH_REPORT.md only for broad architecture review or when query/path/explain do not surface enough context.
- After modifying code, run `graphify update .` to keep the graph current (AST-only, no API cost).
