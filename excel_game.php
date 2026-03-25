<?php
session_start();
if (!isset($_SESSION['username'])) {
    $_SESSION['username'] = "Суралцагч";
}
?>
<!DOCTYPE html>
<html lang="mn">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Excel Quest Game - 20 Даалгавар</title>
<style>
    :root {
        --xl-green: #217346;
        --xl-green-dark: #185c30;
        --xl-green-light: #e8f5ee;
        --ui: #f3f3f3;
        --line: #d4d9e2;
        --text: #222;
        --muted: #666;
        --paper: #fff;
        --panel: #fafafa;
        --cell-border: #d0d7ce;
        --header-bg: #f2f2f2;
        --header-border: #bfc7bb;
        --success: #dff3e8;
        --success-border: #99d0ac;
        --warn: #fff7d6;
        --warn-border: #e0d39a;
        --danger: #ffe2e2;
        --danger-border: #e1aaaa;
        --ribbon-h: 110px;
        --tab-h: 30px;
        --topbar-h: 52px;
        --statusbar-h: 26px;
        --btn-hover: #c6e9d4;
        --btn-active: #a8d9bc;
        --formula-bar-h: 30px;
        --row-header-w: 46px;
        --col-header-h: 22px;
        --cell-h: 22px;
        --cell-w: 80px;
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    html, body { height: 100%; overflow: hidden; }
    body {
        font-family: "Segoe UI", Arial, sans-serif;
        font-size: 12px;
        background: #f3f3f3;
        color: var(--text);
        display: flex;
        flex-direction: column;
    }

    /* ── TITLE BAR ── */
    .title-bar {
        height: var(--topbar-h);
        background: #217346;
        display: flex; align-items: center;
        flex-shrink: 0; position: relative;
    }
    .tb-left { display: flex; align-items: center; gap: 4px; padding: 0 8px; min-width: 200px; }
    .tb-qat { display: flex; gap: 2px; align-items: center; }
    .tb-qat button {
        width: 24px; height: 24px; background: transparent; border: none;
        color: #fff; font-size: 14px; border-radius: 3px; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
    }
    .tb-qat button:hover { background: rgba(255,255,255,0.2); }
    .tb-center {
        position: absolute; left: 50%; transform: translateX(-50%);
        color: #fff; font-size: 13px; white-space: nowrap; pointer-events: none;
    }
    .tb-right { margin-left: auto; display: flex; align-items: center; gap: 8px; padding: 0 12px; }
    .tb-right button {
        background: transparent; border: none; color: #fff;
        padding: 4px 10px; cursor: pointer; font-size: 12px; border-radius: 4px;
    }
    .tb-right button:hover { background: rgba(255,255,255,0.2); }

    /* ── TAB BAR ── */
    .tabs-bar {
        height: var(--tab-h); background: #217346;
        display: flex; align-items: flex-end; padding: 0 2px;
        flex-shrink: 0;
    }
    .tab {
        height: 28px; padding: 0 14px;
        display: flex; align-items: center;
        cursor: pointer; font-size: 12px;
        color: rgba(255,255,255,0.85);
        border-radius: 4px 4px 0 0;
        user-select: none; white-space: nowrap;
    }
    .tab:hover { color: #fff; background: rgba(255,255,255,0.12); }
    .tab.active { background: #f3f3f3; color: #222; }
    .tab.file-tab { background: #185c30; color: #fff; margin-right: 6px; border-radius: 0; }
    .tab.file-tab:hover { background: #0f3d1f; }

    /* ── RIBBON ── */
    .ribbon-area {
        background: #f3f3f3; border-bottom: 1px solid #c8c8c8;
        flex-shrink: 0; min-height: var(--ribbon-h); overflow: hidden;
    }
    .ribbon-panel { display: none; }
    .ribbon-panel.active { display: flex; align-items: stretch; overflow-x: auto; }

    /* ── RIBBON GROUPS ── */
    .rgroup {
        display: flex; flex-direction: column; align-items: flex-start;
        border-right: 1px solid #d4d4d4; padding: 4px 8px 18px;
        min-width: fit-content; position: relative; gap: 2px;
    }
    .rgroup:last-child { border-right: none; }
    .rgroup-title {
        position: absolute; bottom: 3px; left: 0; right: 0;
        text-align: center; font-size: 10px; color: #666; line-height: 1;
    }
    .rgroup-body { display: flex; align-items: flex-start; gap: 2px; flex: 1; }

    /* ── RIBBON BUTTONS ── */
    .rb {
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        gap: 2px; padding: 2px 6px;
        background: transparent; border: 1px solid transparent;
        border-radius: 3px; cursor: pointer; color: var(--text);
        font-size: 11px; line-height: 1.2;
        min-width: 36px; white-space: nowrap;
        font-family: "Segoe UI", Arial, sans-serif;
    }
    .rb:hover { background: var(--btn-hover); border-color: #7ec49a; }
    .rb:active { background: var(--btn-active); }
    .rb .ico { font-size: 24px; line-height: 1; }
    .rb .ico-sm { font-size: 16px; line-height: 1; }
    .rb.large { height: 86px; font-size: 11px; padding: 4px 8px; min-width: 48px; }
    .rb.large .ico { font-size: 32px; }
    .rb.small { flex-direction: row; height: 22px; gap: 4px; font-size: 11px; padding: 1px 6px; min-width: 60px; justify-content: flex-start; }
    .rb.small .ico-sm { font-size: 14px; }
    .rb-col { display: flex; flex-direction: column; gap: 1px; }
    .rb-row { display: flex; align-items: center; gap: 2px; }
    .rb.active-btn { background: var(--btn-active); border-color: #7ec49a; }

    .rsel {
        height: 22px; border: 1px solid #c8c8c8; background: #fff;
        font-size: 11px; border-radius: 2px; color: #222; padding: 0 4px; cursor: pointer;
    }
    .rsel:focus { outline: 1px solid #217346; }

    .fmt-btn {
        width: 24px; height: 22px; background: transparent;
        border: 1px solid transparent; border-radius: 2px;
        cursor: pointer; font-size: 12px; color: var(--text);
        display: flex; align-items: center; justify-content: center;
        font-family: "Segoe UI", Arial, sans-serif;
    }
    .fmt-btn:hover { background: var(--btn-hover); border-color: #7ec49a; }
    .fmt-btn.wide { width: auto; padding: 0 6px; font-size: 11px; }
    .fmt-btn.active-btn { background: var(--btn-active); border-color: #7ec49a; }

    .color-btn-wrap { position: relative; display: flex; flex-direction: column; align-items: center; }
    .color-btn {
        width: 24px; height: 22px; background: transparent;
        border: 1px solid transparent; border-radius: 2px; cursor: pointer;
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        font-size: 12px; font-weight: 700; color: var(--text);
    }
    .color-btn:hover { background: var(--btn-hover); border-color: #7ec49a; }
    .color-bar { width: 18px; height: 4px; border-radius: 1px; }

    .rsep { width: 1px; background: #d4d4d4; margin: 2px 4px; align-self: stretch; }

    /* ── FORMULA BAR ── */
    .formula-bar {
        height: var(--formula-bar-h); background: #fff;
        border-bottom: 1px solid #c8c8c8;
        display: flex; align-items: center; flex-shrink: 0;
    }
    .name-box {
        width: 80px; height: 100%; border: none; border-right: 1px solid #c8c8c8;
        text-align: center; font-size: 12px; padding: 0 6px;
        font-family: "Segoe UI", Arial, sans-serif; cursor: pointer;
        background: #fff; color: var(--text);
    }
    .name-box:focus { outline: 1px solid #217346; }
    .formula-icons {
        display: flex; align-items: center; padding: 0 4px; gap: 2px;
        border-right: 1px solid #c8c8c8;
    }
    .fx-btn {
        height: 22px; padding: 0 6px; background: transparent; border: none;
        cursor: pointer; font-size: 12px; color: var(--muted);
        display: flex; align-items: center;
    }
    .fx-btn:hover { color: #217346; }
    .formula-input {
        flex: 1; height: 100%; border: none; padding: 0 8px;
        font-size: 12px; font-family: "Segoe UI", Arial, sans-serif;
        background: #fff; color: var(--text);
    }
    .formula-input:focus { outline: none; }

    /* ── WORKSPACE ── */
    .workspace {
        display: grid; grid-template-columns: 1fr 320px;
        flex: 1; overflow: hidden; min-height: 0;
    }
    .spreadsheet-wrap {
        overflow: auto; background: #fff; position: relative;
        border-right: 1px solid var(--line);
    }

    /* ── SPREADSHEET ── */
    .grid-container { display: flex; flex-direction: column; user-select: none; }
    .grid-header-row {
        display: flex; position: sticky; top: 0; z-index: 10;
        background: var(--header-bg); border-bottom: 1px solid var(--header-border);
    }
    .corner-cell {
        width: var(--row-header-w); min-width: var(--row-header-w);
        height: var(--col-header-h);
        background: var(--header-bg); border-right: 1px solid var(--header-border);
        border-bottom: 1px solid var(--header-border);
        cursor: pointer; display: flex; align-items: center; justify-content: center;
        font-size: 11px; color: var(--muted); flex-shrink: 0;
    }
    .col-header {
        height: var(--col-header-h); min-width: var(--cell-w);
        border-right: 1px solid var(--cell-border);
        background: var(--header-bg);
        display: flex; align-items: center; justify-content: center;
        font-size: 11px; color: var(--text); cursor: pointer;
        position: relative; flex-shrink: 0;
    }
    .col-header:hover { background: #d6e8dc; }
    .col-resize {
        position: absolute; right: 0; top: 0; width: 4px; height: 100%;
        cursor: col-resize; z-index: 1;
    }
    .grid-row { display: flex; border-bottom: 1px solid var(--cell-border); }
    .row-header {
        width: var(--row-header-w); min-width: var(--row-header-w);
        height: var(--cell-h); border-right: 1px solid var(--cell-border);
        background: var(--header-bg);
        display: flex; align-items: center; justify-content: center;
        font-size: 11px; color: var(--text); cursor: pointer; flex-shrink: 0;
    }
    .cell {
        height: var(--cell-h); min-width: var(--cell-w);
        border-right: 1px solid var(--cell-border);
        padding: 0 4px; font-size: 12px; overflow: hidden;
        white-space: nowrap; cursor: cell;
        position: relative; outline: none; flex-shrink: 0;
        display: flex; align-items: center;
        background: #fff; color: #222;
    }
    .cell:hover { background: #f0f8f3; }
    .cell.selected { outline: 2px solid #217346 !important; outline-offset: -2px; z-index: 5; }
    .cell.in-range { background: #e8f5ee !important; }
    .cell.editing { outline: 2px solid #217346 !important; outline-offset: -2px; z-index: 10; padding: 0; }
    .cell-editor {
        width: 100%; height: 100%; border: none; padding: 0 4px;
        font-size: 12px; font-family: "Segoe UI", Arial, sans-serif;
        background: #fff; color: #222; outline: none;
    }

    /* ── SHEET TABS ── */
    .sheet-tabs-bar {
        height: 26px; background: var(--ui);
        border-top: 1px solid var(--line);
        display: flex; align-items: center;
        flex-shrink: 0; padding: 0 4px; gap: 2px;
    }
    .sheet-tab {
        height: 22px; padding: 0 12px;
        display: flex; align-items: center;
        cursor: pointer; font-size: 11px; color: var(--muted);
        border: 1px solid transparent; border-radius: 2px 2px 0 0;
        white-space: nowrap;
    }
    .sheet-tab:hover { background: var(--btn-hover); color: var(--text); }
    .sheet-tab.active { background: #fff; color: #222; border-color: var(--line); border-bottom: none; font-weight: 600; }
    .sheet-tab-add {
        width: 22px; height: 22px; background: transparent; border: none;
        font-size: 16px; cursor: pointer; color: var(--muted);
        display: flex; align-items: center; justify-content: center; border-radius: 2px;
    }
    .sheet-tab-add:hover { background: var(--btn-hover); color: var(--text); }

    /* ── STATUS BAR ── */
    .status-bar {
        height: var(--statusbar-h); background: #217346; color: #fff;
        display: flex; align-items: center; justify-content: space-between;
        padding: 0 10px; font-size: 12px; flex-shrink: 0;
    }
    .sb-left { display: flex; gap: 16px; align-items: center; }
    .sb-right { display: flex; gap: 12px; align-items: center; }
    .sb-sep { width: 1px; height: 14px; background: rgba(255,255,255,0.3); }

    /* ── DIALOGS ── */
    .dialog-backdrop {
        position: fixed; inset: 0; background: rgba(0,0,0,0.35);
        display: none; align-items: center; justify-content: center; z-index: 999;
    }
    .dialog-backdrop.show { display: flex; }
    .dialog {
        width: 460px; max-width: 92vw; background: #fff; border-radius: 8px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.22); padding: 20px; color: #222;
    }
    .dialog h3 { margin-bottom: 14px; color: #217346; }
    .dialog .form-row { margin-bottom: 10px; display: flex; flex-direction: column; gap: 4px; }
    .dialog .form-row label { font-size: 12px; color: #555; }
    .dialog .form-row input, .dialog .form-row select, .dialog .form-row textarea {
        height: 28px; border: 1px solid #ccc; border-radius: 4px;
        padding: 0 8px; font-size: 13px; font-family: "Segoe UI", Arial, sans-serif;
    }
    .dialog .actions { display: flex; justify-content: flex-end; gap: 8px; margin-top: 12px; }
    .btn-ok { background: #217346; color: #fff; border: none; border-radius: 4px; padding: 6px 16px; cursor: pointer; font-size: 12px; }
    .btn-ok:hover { background: #185c30; }
    .btn-cancel { background: #fff; border: 1px solid #ccc; border-radius: 4px; padding: 6px 16px; cursor: pointer; font-size: 12px; }

    /* ── CONTEXT MENU ── */
    .context-menu {
        position: fixed; background: #fff; border: 1px solid #ccc;
        border-radius: 4px; box-shadow: 0 4px 16px rgba(0,0,0,0.15);
        z-index: 9999; min-width: 160px; display: none; padding: 4px 0;
    }
    .context-menu.show { display: block; }
    .ctx-item { padding: 6px 14px; font-size: 12px; cursor: pointer; display: flex; align-items: center; gap: 8px; color: #222; }
    .ctx-item:hover { background: #e8f5ee; }
    .ctx-sep { height: 1px; background: #e0e0e0; margin: 3px 0; }

    .hidden { display: none !important; }

    /* ════════════════════════════════
       QUEST SIDE PANEL — NEW DESIGN
       ════════════════════════════════ */
    .side-panel {
        background: #f8faf8;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    /* Quest progress header */
    .quest-header {
        background: linear-gradient(135deg, #185c30 0%, #217346 100%);
        padding: 14px 16px 10px;
        flex-shrink: 0;
    }
    .quest-header-top {
        display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;
    }
    .quest-title { color: #fff; font-size: 15px; font-weight: 700; }
    .xp-badge {
        background: rgba(255,255,255,0.2);
        color: #fff; font-size: 12px; font-weight: 600;
        padding: 3px 10px; border-radius: 20px;
    }
    .progress-bar-wrap {
        background: rgba(255,255,255,0.2); border-radius: 999px; height: 8px; overflow: hidden;
    }
    .progress-bar-fill {
        height: 100%; background: #7effc0; border-radius: 999px;
        transition: width 0.5s ease;
    }
    .progress-label {
        color: rgba(255,255,255,0.85); font-size: 11px; margin-top: 5px;
        display: flex; justify-content: space-between;
    }

    /* Task list */
    .task-list-wrap {
        padding: 10px 12px 6px;
        flex-shrink: 0;
        max-height: 140px;
        overflow-y: auto;
        border-bottom: 1px solid #e0e8e0;
    }
    .task-list-title { font-size: 11px; font-weight: 700; color: #555; margin-bottom: 6px; letter-spacing: 0.05em; }
    .task-item {
        display: flex; align-items: center; gap: 8px;
        padding: 4px 8px; margin-bottom: 2px; border-radius: 6px;
        cursor: pointer; font-size: 12px;
        transition: background 0.15s;
        border: 1px solid transparent;
    }
    .task-item:hover { background: #e8f5ee; }
    .task-item.current {
        background: #e8f5ee; border-color: #217346; font-weight: 600; color: #185c30;
    }
    .task-item.done {
        color: #888; text-decoration: line-through;
    }
    .task-item .task-num {
        width: 20px; height: 20px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 10px; font-weight: 700; flex-shrink: 0;
        background: #ddd; color: #666;
    }
    .task-item.current .task-num { background: #217346; color: #fff; }
    .task-item.done .task-num { background: #a8d9bc; color: #185c30; }
    .task-icon { font-size: 11px; }

    /* Current task detail */
    .current-task-card {
        flex: 1;
        overflow-y: auto;
        padding: 12px 14px;
    }
    .task-card-inner {
        background: #fff; border: 1px solid #d4e8d4; border-radius: 10px;
        padding: 14px; margin-bottom: 10px;
        box-shadow: 0 1px 4px rgba(33,115,70,0.06);
    }
    .task-card-header {
        display: flex; align-items: center; gap: 8px; margin-bottom: 10px;
    }
    .task-card-icon { font-size: 22px; }
    .task-card-title { font-size: 14px; font-weight: 700; color: #185c30; }
    .task-card-subtitle { font-size: 11px; color: #888; margin-top: 1px; }
    .task-desc {
        font-size: 13px; line-height: 1.7; color: #333;
        background: #f8fdf8; border-radius: 6px; padding: 10px 12px;
        border-left: 3px solid #217346; margin-bottom: 10px;
    }
    .task-steps { list-style: none; margin-bottom: 10px; }
    .task-steps li {
        display: flex; align-items: flex-start; gap: 8px;
        font-size: 12px; line-height: 1.5; padding: 4px 0;
        border-bottom: 1px dashed #e8f0e8;
    }
    .task-steps li:last-child { border-bottom: none; }
    .step-num {
        width: 18px; height: 18px; border-radius: 50%; background: #217346; color: #fff;
        font-size: 10px; font-weight: 700; display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; margin-top: 1px;
    }

    /* Hint box */
    .hint-card {
        background: #fffbea; border: 1px solid #f0d060; border-radius: 8px;
        padding: 10px 12px; margin-bottom: 10px;
    }
    .hint-toggle {
        display: flex; align-items: center; gap: 6px; cursor: pointer;
        font-size: 12px; font-weight: 600; color: #8a6a00;
        user-select: none;
    }
    .hint-toggle:hover { color: #5a4400; }
    .hint-body {
        font-size: 12px; line-height: 1.7; color: #5a4400;
        margin-top: 8px; display: none;
    }
    .hint-body.show { display: block; }
    .hint-body code {
        background: #fff3cc; padding: 1px 5px; border-radius: 3px;
        font-family: "Courier New", monospace; font-size: 11px;
    }

    /* Check button */
    .check-btn {
        width: 100%; height: 44px; border: 0;
        background: linear-gradient(135deg, #217346 0%, #185c30 100%);
        color: #fff; border-radius: 8px; font-size: 15px; font-weight: 700;
        cursor: pointer; margin-bottom: 10px;
        display: flex; align-items: center; justify-content: center; gap: 8px;
        transition: transform 0.1s, box-shadow 0.1s;
        box-shadow: 0 3px 10px rgba(33,115,70,0.3);
    }
    .check-btn:hover { transform: translateY(-1px); box-shadow: 0 5px 16px rgba(33,115,70,0.4); }
    .check-btn:active { transform: translateY(0); }

    /* Status box */
    .status-result {
        border-radius: 8px; padding: 10px 12px;
        font-size: 13px; line-height: 1.5; color: #222;
        border: 1px solid var(--warn-border); background: var(--warn);
        min-height: 44px; display: flex; align-items: flex-start;
    }
    .status-result.success { background: var(--success); border-color: var(--success-border); color: #185c30; }
    .status-result.error { background: var(--danger); border-color: var(--danger-border); color: #8b0000; }

    /* Stats mini */
    .stats-row {
        display: flex; gap: 6px; margin-top: 8px;
    }
    .stat-pill {
        flex: 1; background: #f0f8f0; border: 1px solid #c8e4c8;
        border-radius: 6px; padding: 4px 6px; text-align: center;
    }
    .stat-pill .sv { font-size: 13px; font-weight: 700; color: #217346; }
    .stat-pill .sl { font-size: 10px; color: #888; }

    /* Celebration overlay */
    .celebrate-overlay {
        position: fixed; inset: 0; pointer-events: none; z-index: 9998;
        display: none;
    }
    .celebrate-overlay.show { display: block; }
    .confetti-piece {
        position: absolute; width: 10px; height: 10px; border-radius: 2px;
        animation: fall 2.5s ease-in forwards;
    }
    @keyframes fall {
        0% { transform: translateY(-20px) rotate(0deg); opacity: 1; }
        100% { transform: translateY(100vh) rotate(720deg); opacity: 0; }
    }

    @media (max-width: 1100px) {
        .workspace { grid-template-columns: 1fr; }
        .side-panel { display: none; }
    }
</style>
</head>
<body>

<!-- ══ TITLE BAR ══ -->
<div class="title-bar">
    <div class="tb-left">
        <svg width="18" height="18" viewBox="0 0 18 18" style="flex-shrink:0">
            <rect width="18" height="18" rx="2" fill="#fff"/>
            <text x="2" y="14" font-size="12" font-weight="700" fill="#217346">X</text>
        </svg>
        <div class="tb-qat">
            <button onclick="saveWorkbook()" title="Save (Ctrl+S)">💾</button>
            <button onclick="undoAction()" title="Undo (Ctrl+Z)">↶</button>
            <button onclick="redoAction()" title="Redo (Ctrl+Y)">↷</button>
            <button onclick="printWorkbook()" title="Print">🖨️</button>
        </div>
    </div>
    <div class="tb-center">Workbook1.xlsx — Excel Quest 🎯 20 Даалгавар</div>
    <div class="tb-right">
        <span style="color:#fff;font-size:12px;">👤 <?= htmlspecialchars($_SESSION['username']) ?></span>
    </div>
</div>

<!-- ══ TAB BAR ══ -->
<div class="tabs-bar" id="tabsBar">
    <div class="tab active" data-tab="home">Home</div>
    <div class="tab" data-tab="insert">Insert</div>
    <div class="tab" data-tab="formulas">Formulas</div>
    <div class="tab" data-tab="data">Data</div>
    <div class="tab" data-tab="view">View</div>
</div>

<!-- ══ RIBBON ══ -->
<div class="ribbon-area">

    <!-- HOME -->
    <div class="ribbon-panel active" id="panel-home">
        <div class="rgroup">
            <div class="rgroup-body">
                <div class="rb-col">
                    <button class="rb large" onclick="pasteCell()"><span class="ico">📋</span>Paste</button>
                </div>
                <div class="rb-col">
                    <button class="rb small" onclick="cutCell()"><span class="ico-sm">✂️</span>Cut</button>
                    <button class="rb small" onclick="copyCell()"><span class="ico-sm">📄</span>Copy</button>
                    <button class="rb small" onclick="pasteCell()"><span class="ico-sm">📋</span>Paste</button>
                </div>
            </div>
            <div class="rgroup-title">Clipboard</div>
        </div>

        <div class="rgroup">
            <div class="rgroup-body">
                <div class="rb-col" style="gap:3px;">
                    <div class="rb-row">
                        <select id="fontFamily" class="rsel" style="width:130px;" onchange="applyCellFont(this.value)">
                            <option value="Calibri">Calibri</option>
                            <option value="Arial">Arial</option>
                            <option value="Times New Roman">Times New Roman</option>
                            <option value="Verdana">Verdana</option>
                            <option value="Courier New">Courier New</option>
                        </select>
                        <select id="fontSize" class="rsel" style="width:46px;" onchange="applyCellFontSize(this.value)">
                            <option>8</option><option>9</option><option>10</option>
                            <option selected>11</option><option>12</option><option>14</option>
                            <option>16</option><option>18</option><option>20</option>
                            <option>24</option><option>28</option><option>36</option>
                            <option>48</option><option>72</option>
                        </select>
                        <button class="fmt-btn wide" onclick="growCellFont()">A▲</button>
                        <button class="fmt-btn wide" onclick="shrinkCellFont()">A▼</button>
                    </div>
                    <div class="rb-row">
                        <button class="fmt-btn" id="btnBold" onclick="toggleCellBold()"><b>B</b></button>
                        <button class="fmt-btn" id="btnItalic" onclick="toggleCellItalic()"><i>I</i></button>
                        <button class="fmt-btn" id="btnUnderline" onclick="toggleCellUnderline()" style="text-decoration:underline;">U</button>
                        <div class="rsep"></div>
                        <div class="color-btn-wrap">
                            <button class="color-btn" onclick="document.getElementById('fillColorInput').click()">
                                <span>🪣</span>
                                <div class="color-bar" id="fillColorBar" style="background:#ffff00;"></div>
                            </button>
                            <input type="color" id="fillColorInput" value="#ffff00" onchange="applyCellFill(this.value)" style="position:absolute;opacity:0;width:0;height:0;">
                        </div>
                        <div class="color-btn-wrap">
                            <button class="color-btn" onclick="document.getElementById('fontColorInput').click()">
                                <span style="font-weight:700;color:#e8000d;">A</span>
                                <div class="color-bar" id="fontColorBar" style="background:#e8000d;"></div>
                            </button>
                            <input type="color" id="fontColorInput" value="#e8000d" onchange="applyCellFontColor(this.value)" style="position:absolute;opacity:0;width:0;height:0;">
                        </div>
                    </div>
                </div>
            </div>
            <div class="rgroup-title">Font</div>
        </div>

        <div class="rgroup">
            <div class="rgroup-body">
                <div class="rb-col" style="gap:3px;">
                    <div class="rb-row">
                        <button class="fmt-btn wide" id="btnLeft" onclick="applyCellAlign('left')">≡⬅</button>
                        <button class="fmt-btn wide" id="btnCenter" onclick="applyCellAlign('center')">≡↔</button>
                        <button class="fmt-btn wide" id="btnRight" onclick="applyCellAlign('right')">⬅≡</button>
                        <div class="rsep"></div>
                        <button class="rb small" onclick="mergeCells()" style="min-width:80px;">⊞ Merge</button>
                    </div>
                    <div class="rb-row">
                        <button class="fmt-btn wide" onclick="toggleWrapText()">↵ Wrap</button>
                    </div>
                </div>
            </div>
            <div class="rgroup-title">Alignment</div>
        </div>

        <div class="rgroup">
            <div class="rgroup-body">
                <div class="rb-col" style="gap:3px;">
                    <select id="numFormat" class="rsel" style="width:128px;" onchange="applyNumberFormat(this.value)">
                        <option value="general">General</option>
                        <option value="number">Number</option>
                        <option value="currency">Currency</option>
                        <option value="percent">Percentage</option>
                        <option value="text">Text</option>
                    </select>
                    <div class="rb-row">
                        <button class="fmt-btn wide" onclick="applyNumberFormat('currency')">$</button>
                        <button class="fmt-btn wide" onclick="applyNumberFormat('percent')">%</button>
                        <button class="fmt-btn wide" onclick="applyNumberFormat('number')">,</button>
                    </div>
                </div>
            </div>
            <div class="rgroup-title">Number</div>
        </div>

        <div class="rgroup">
            <div class="rgroup-body">
                <div class="rb-col">
                    <button class="rb small" onclick="autoSumSelection()"><span class="ico-sm">Σ</span>AutoSum</button>
                    <button class="rb small" onclick="fillDown()"><span class="ico-sm">⬇</span>Fill Down</button>
                    <button class="rb small" onclick="clearCells()"><span class="ico-sm">🗑️</span>Clear</button>
                    <button class="rb small" onclick="openFindDialog()"><span class="ico-sm">🔍</span>Find & Replace</button>
                    <button class="rb small" onclick="sortAsc()"><span class="ico-sm">⬆</span>Sort A→Z</button>
                </div>
            </div>
            <div class="rgroup-title">Editing</div>
        </div>

        <div class="rgroup">
            <div class="rgroup-body">
                <button class="rb large" onclick="checkTask()" style="background:#217346;color:#fff;border-radius:6px;">
                    <span class="ico">🎯</span>Check
                </button>
            </div>
            <div class="rgroup-title">Quest</div>
        </div>
    </div>

    <!-- FORMULAS -->
    <div class="ribbon-panel" id="panel-formulas">
        <div class="rgroup">
            <div class="rgroup-body">
                <div class="rb-col">
                    <button class="rb large" onclick="insertFunction()"><span class="ico">fx</span>Insert Function</button>
                </div>
                <div class="rb-col">
                    <button class="rb small" onclick="autoSumSelection()"><span class="ico-sm">Σ</span>AutoSum</button>
                    <button class="rb small" onclick="showFormulas()"><span class="ico-sm">fx</span>Show Formulas</button>
                    <button class="rb small" onclick="evaluateFormula()"><span class="ico-sm">▶</span>Evaluate</button>
                    <button class="rb small" onclick="errorCheck()"><span class="ico-sm">⚠️</span>Error Checking</button>
                </div>
            </div>
            <div class="rgroup-title">Function Library</div>
        </div>
    </div>

    <!-- INSERT -->
    <div class="ribbon-panel" id="panel-insert">
        <div class="rgroup">
            <div class="rgroup-body">
                <button class="rb large" onclick="insertTable()"><span class="ico">⊞</span>Table</button>
            </div>
            <div class="rgroup-title">Tables</div>
        </div>
        <div class="rgroup">
            <div class="rgroup-body">
                <div class="rb-row">
                    <button class="rb" onclick="insertBarChart()"><span class="ico-sm">📊</span><span>Bar</span></button>
                    <button class="rb" onclick="insertLineChart()"><span class="ico-sm">📈</span><span>Line</span></button>
                    <button class="rb" onclick="insertPieChart()"><span class="ico-sm">🥧</span><span>Pie</span></button>
                </div>
            </div>
            <div class="rgroup-title">Charts</div>
        </div>
        <div class="rgroup">
            <div class="rgroup-body">
                <button class="rb small" onclick="openLinkDialog()"><span class="ico-sm">🔗</span>Hyperlink</button>
                <button class="rb small" onclick="insertComment()"><span class="ico-sm">💬</span>Comment</button>
            </div>
            <div class="rgroup-title">Links</div>
        </div>
    </div>

    <!-- DATA -->
    <div class="ribbon-panel" id="panel-data">
        <div class="rgroup">
            <div class="rgroup-body">
                <div class="rb-col">
                    <button class="rb small" onclick="sortAsc()"><span class="ico-sm">⬆</span>Sort A→Z</button>
                    <button class="rb small" onclick="sortDesc()"><span class="ico-sm">⬇</span>Sort Z→A</button>
                    <button class="rb small" onclick="toggleAutoFilter()"><span class="ico-sm">⬇</span>Filter</button>
                    <button class="rb small" onclick="removeDuplicates()"><span class="ico-sm">✕</span>Remove Duplicates</button>
                </div>
            </div>
            <div class="rgroup-title">Sort & Filter</div>
        </div>
    </div>

    <!-- VIEW -->
    <div class="ribbon-panel" id="panel-view">
        <div class="rgroup">
            <div class="rgroup-body">
                <button class="rb large" onclick="toggleDarkMode()"><span class="ico">🌙</span>Dark Mode</button>
            </div>
            <div class="rgroup-title">Theme</div>
        </div>
        <div class="rgroup">
            <div class="rgroup-body">
                <div class="rb-col">
                    <button class="rb small" onclick="setZoom(0.75)"><span class="ico-sm">🔍</span>75%</button>
                    <button class="rb small" onclick="setZoom(1)"><span class="ico-sm">🔍</span>100%</button>
                    <button class="rb small" onclick="setZoom(1.25)"><span class="ico-sm">🔍</span>125%</button>
                </div>
            </div>
            <div class="rgroup-title">Zoom</div>
        </div>
    </div>
</div>

<!-- ══ FORMULA BAR ══ -->
<div class="formula-bar">
    <input type="text" class="name-box" id="nameBox" value="A1" onclick="this.select()" onchange="goToCell(this.value)">
    <div class="formula-icons">
        <button class="fx-btn" onclick="cancelEdit()">✕</button>
        <button class="fx-btn" onclick="confirmEdit()">✓</button>
        <button class="fx-btn" onclick="insertFunction()" style="font-style:italic;font-weight:700;font-size:14px;color:#217346;">fx</button>
    </div>
    <input type="text" id="formulaInput" class="formula-input" placeholder="Утга эсвэл формул оруулах" onkeydown="formulaBarKeyDown(event)" oninput="formulaBarInput()">
</div>

<!-- ══ WORKSPACE ══ -->
<div class="workspace">
    <div class="spreadsheet-wrap" id="spreadsheetWrap">
        <div class="grid-container" id="gridContainer"></div>
    </div>

    <!-- ══ QUEST SIDE PANEL ══ -->
    <div class="side-panel">

        <!-- Progress Header -->
        <div class="quest-header">
            <div class="quest-header-top">
                <div class="quest-title">🎯 Excel Quest</div>
                <div class="xp-badge">⭐ <span id="xpDisplay">0</span> XP</div>
            </div>
            <div class="progress-bar-wrap">
                <div class="progress-bar-fill" id="progressBar" style="width:0%"></div>
            </div>
            <div class="progress-label">
                <span>Даалгавар <span id="progressCurrent">1</span>/20</span>
                <span id="progressPct">0%</span>
            </div>
        </div>

        <!-- Task List Scrollable -->
        <div class="task-list-wrap">
            <div class="task-list-title">ДААЛГАВРУУДЫН ЖАГСААЛТ</div>
            <div id="taskList"></div>
        </div>

        <!-- Current Task Detail -->
        <div class="current-task-card" id="currentTaskCard"></div>
    </div>
</div>

<!-- Sheet Tabs -->
<div class="sheet-tabs-bar" id="sheetTabsBar">
    <button class="sheet-tab-add" onclick="addSheet()">+</button>
</div>

<!-- ══ STATUS BAR ══ -->
<div class="status-bar">
    <div class="sb-left">
        <span id="statusMsg">Ready</span>
        <div class="sb-sep"></div>
        <span id="sbCellMode">Normal</span>
    </div>
    <div class="sb-right">
        <span>Sum: <b id="sbSumStatus">0</b></span>
        <span>Avg: <b id="sbAvgStatus">0</b></span>
        <span>Count: <b id="sbCntStatus">0</b></span>
        <div class="sb-sep"></div>
        <span id="zoomStatus">100%</span>
    </div>
</div>

<!-- ══ DIALOGS ══ -->
<div class="dialog-backdrop" id="findDialog">
    <div class="dialog">
        <h3>🔍 Find & Replace</h3>
        <div class="form-row"><label>Find</label><input type="text" id="findInput" placeholder="Хайх текст..."></div>
        <div class="form-row"><label>Replace with</label><input type="text" id="replaceInput" placeholder="Солих текст..."></div>
        <div class="actions">
            <button class="btn-cancel" onclick="closeDialog('findDialog')">Cancel</button>
            <button class="btn-ok" onclick="doFind()">Find Next</button>
            <button class="btn-ok" onclick="doReplace()">Replace All</button>
        </div>
    </div>
</div>

<div class="dialog-backdrop" id="linkDialog">
    <div class="dialog">
        <h3>🔗 Холбоос оруулах</h3>
        <div class="form-row"><label>URL</label><input type="text" id="linkUrl" placeholder="https://example.com"></div>
        <div class="form-row"><label>Харуулах текст</label><input type="text" id="linkText"></div>
        <div class="actions">
            <button class="btn-cancel" onclick="closeDialog('linkDialog')">Cancel</button>
            <button class="btn-ok" onclick="insertLinkFromDialog()">Insert</button>
        </div>
    </div>
</div>

<div class="dialog-backdrop" id="commentDialog">
    <div class="dialog">
        <h3>💬 Comment оруулах</h3>
        <div class="form-row"><label>Тайлбар</label><textarea id="commentText" rows="3" style="height:60px;padding:6px 8px;resize:none;"></textarea></div>
        <div class="actions">
            <button class="btn-cancel" onclick="closeDialog('commentDialog')">Cancel</button>
            <button class="btn-ok" onclick="saveComment()">OK</button>
        </div>
    </div>
</div>

<!-- Context Menu -->
<div class="context-menu" id="contextMenu">
    <div class="ctx-item" onclick="cutCell()">✂️ Cut</div>
    <div class="ctx-item" onclick="copyCell()">📄 Copy</div>
    <div class="ctx-item" onclick="pasteCell()">📋 Paste</div>
    <div class="ctx-sep"></div>
    <div class="ctx-item" onclick="clearCells()">🗑️ Clear Contents</div>
    <div class="ctx-item" onclick="autoSumSelection()">Σ AutoSum</div>
</div>

<!-- Celebrate overlay -->
<div class="celebrate-overlay" id="celebrateOverlay"></div>

<script>
// ════════════════════════════
// CONSTANTS
// ════════════════════════════
const COLS = 20, ROWS = 40;
const COL_LETTERS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.split('');

// ════════════════════════════
// 20 TASKS DEFINITION
// ════════════════════════════
const TASKS = [
    {
        id: 1, icon: '✍️', title: 'Текст бичих',
        subtitle: 'A1 нүдэнд текст оруулах',
        desc: 'A1 нүдэнд <b>"Орлого"</b> гэж бичнэ үү.',
        steps: ['A1 нүдийг сонгоно', '"Орлого" гэж бичнэ', 'Enter дарна'],
        hint: '<b>Арга:</b> A1 нүд дээр дарж, гараас "Орлого" гэж бичиж Enter дарна уу.\n<b>Шинжилгээ:</b> Бичсэн утга зүүн талд харагдана.',
        xp: 5,
        check: () => {
            const v = String(getCell(0,0).value||'').trim();
            return { ok: v === 'Орлого', msg: v !== 'Орлого' ? `A1 нүдэнд "Орлого" гэж бичнэ үү. Одоогийн утга: "${v}"` : '' };
        }
    },
    {
        id: 2, icon: '🔠', title: 'Bold хийх',
        subtitle: 'А1-ийн текстийг тод болгох',
        desc: 'A1 нүдийн текстийг <b>Bold (тод)</b> хийнэ үү.',
        steps: ['A1 нүдийг сонгоно', 'Home → Font → Bold товчийг дарна', 'эсвэл Ctrl+B дарна'],
        hint: '<b>Товчлол:</b> <code>Ctrl + B</code>\n<b>Ribbon:</b> Home → Font хэсгийн <b>B</b> товч',
        xp: 5,
        check: () => {
            const c = getCell(0,0);
            const ok = !!c.bold && String(c.value||'').trim()==='Орлого';
            return { ok, msg: !ok ? (!c.bold ? 'A1 нүдийг Bold хийнэ үү (Ctrl+B).' : 'Эхлээд A1-д "Орлого" бичнэ үү.') : '' };
        }
    },
    {
        id: 3, icon: '↔️', title: 'Текстийг голлуулах',
        subtitle: 'А1-ийг Center align хийх',
        desc: 'A1 нүдийн текстийг <b>Center (голлуулах)</b> хийнэ үү.',
        steps: ['A1 нүдийг сонгоно', 'Home → Alignment → Center товч дарна', 'Текст голдоо шилжинэ'],
        hint: '<b>Ribbon:</b> Home → Alignment хэсгийн <b>≡↔</b> товч дарна\n<b>Харах:</b> Текст нүдний дунд байрлана.',
        xp: 5,
        check: () => {
            const c = getCell(0,0);
            const ok = c.align === 'center' && String(c.value||'').trim()==='Орлого';
            return { ok, msg: !ok ? 'A1 нүдийг Center align хийнэ үү.' : '' };
        }
    },
    {
        id: 4, icon: '🎨', title: 'Дэвсгэр өнгө',
        subtitle: 'А1-д ногоон дэвсгэр өнгө өгөх',
        desc: 'A1 нүдийн дэвсгэр өнгийг <b>#217346 (ногоон)</b> болгоно уу.',
        steps: ['A1 нүдийг сонгоно', 'Home → Font → 🪣 (Fill Color) дарна', 'Өнгийн код #217346 оруулна'],
        hint: '<b>Fill Color:</b> Ribbon дээрх 🪣 товч дарж өнгийн picker нээгдэнэ.\n<b>Hex код:</b> <code>#217346</code> — энэ бол Excel-ийн ногоон өнгө.',
        xp: 10,
        check: () => {
            const c = getCell(0,0);
            const fill = (c.fill||'').toLowerCase().replace(/\s/g,'');
            const ok = (fill === '#217346') && String(c.value||'').trim()==='Орлого';
            return { ok, msg: !ok ? `Дэвсгэр өнгийг #217346 болгоно уу. Одоогийн: ${c.fill||'хоосон'}` : '' };
        }
    },
    {
        id: 5, icon: '🤍', title: 'Фонт өнгө',
        subtitle: 'А1-ийн текстийг цагаан болгох',
        desc: 'A1 нүдийн <b>фонт өнгийг цагаан (#ffffff)</b> болгоно уу.',
        steps: ['A1 нүдийг сонгоно', 'Home → Font → A (Font Color) дарна', 'Цагаан (#ffffff) өнгийг сонгоно'],
        hint: '<b>Font Color:</b> <code>A</code> үсэгтэй өнгийн товч дарна.\n<b>Нотолгоо:</b> Ногоон дэвсгэр дээр цагаан текст харагдана.',
        xp: 10,
        check: () => {
            const c = getCell(0,0);
            const fc = (c.fontColor||'').toLowerCase().replace(/\s/g,'');
            const ok = (fc==='#ffffff'||fc==='#fff'||fc==='white') && String(c.value||'').trim()==='Орлого';
            return { ok, msg: !ok ? `Фонт өнгийг #ffffff (цагаан) болгоно уу. Одоогийн: ${c.fontColor||'анхдагч'}` : '' };
        }
    },
    {
        id: 6, icon: '🔢', title: 'Тоо оруулах',
        subtitle: 'B1-д тоо бичих',
        desc: 'B1 нүдэнд <b>1500</b> гэж бичнэ үү.',
        steps: ['B1 нүдийг сонгоно (Col B, Row 1)', '1500 гэж бичнэ', 'Enter дарна'],
        hint: '<b>Байршил:</b> A баганын хажуу нь B багана.\n<b>Тоо:</b> Тоо оруулахад баруун талд харагдана.',
        xp: 5,
        check: () => {
            const v = parseFloat(getCell(0,1).value);
            return { ok: v === 1500, msg: v !== 1500 ? `B1-д 1500 оруулна уу. Одоогийн: ${getCell(0,1).value||'хоосон'}` : '' };
        }
    },
    {
        id: 7, icon: '📊', title: 'Формул ашиглах',
        subtitle: '=SUM() формул ашиглах',
        desc: 'B2-д <b>500</b>, B3-д <b>750</b> бичгээд B4-д <code>=SUM(B1:B3)</code> формул оруулна уу.',
        steps: ['B2-д 500 бичнэ', 'B3-д 750 бичнэ', 'B4-д =SUM(B1:B3) бичнэ', 'Enter дарна → 2750 гарна'],
        hint: '<b>SUM формул:</b> <code>=SUM(B1:B3)</code>\nЭнэ нь B1-ээс B3 хүртэл нийлбэр тооцно.\n<b>Хүлээгдэх үр дүн:</b> 1500+500+750 = <b>2750</b>',
        xp: 15,
        check: () => {
            const b2 = parseFloat(getCell(1,1).value), b3 = parseFloat(getCell(2,1).value);
            const b4 = getCell(3,1);
            const val = b4.formula ? evalFormula(b4.formula,3,1) : parseFloat(b4.value);
            const ok = b2===500 && b3===750 && Math.round(parseFloat(val))===2750;
            return { ok, msg: !ok ? `B2=500, B3=750 бичгээд B4-д =SUM(B1:B3) оруулна уу. Одоогийн B4: ${b4.value||b4.formula||'хоосон'}` : '' };
        }
    },
    {
        id: 8, icon: '💲', title: 'Мөнгөн тоо',
        subtitle: 'Currency форматлах',
        desc: 'B1:B4 нүднүүдийг сонгоод <b>Currency ($)</b> форматыг хэрэглэнэ үү.',
        steps: ['B1-г дарна', 'Shift дарж B4 хүртэл сонгоно', 'Home → Number → $ дарна'],
        hint: '<b>Сонгох:</b> B1 дарж Shift+B4 дарна.\n<b>Currency:</b> Ribbon-ий <b>$</b> товч дарна.\n<b>Үр дүн:</b> $1,500.00 хэлбэртэй харагдана.',
        xp: 10,
        check: () => {
            let ok = true;
            for(let r=0;r<4;r++) { if((getCell(r,1).numFmt||'')!=='currency') ok=false; }
            return { ok, msg: !ok ? 'B1:B4-ийг сонгоод Currency форматыг хэрэглэнэ үү.' : '' };
        }
    },
    {
        id: 9, icon: '🔤', title: 'Italic хийх',
        subtitle: 'Текстийг налуу болгох',
        desc: 'A1 нүдийг <b>Italic (налуу)</b> хийнэ үү.',
        steps: ['A1 нүдийг сонгоно', 'Ctrl+I дарна', 'эсвэл Home → Font → I товч'],
        hint: '<b>Товчлол:</b> <code>Ctrl + I</code>\n<b>Харах:</b> Текст налуу харагдана.',
        xp: 5,
        check: () => {
            const c = getCell(0,0);
            return { ok: !!c.italic, msg: !c.italic ? 'A1-г Italic болгоно уу (Ctrl+I).' : '' };
        }
    },
    {
        id: 10, icon: '📝', title: 'Олон нүд бичих',
        subtitle: 'C1:C5-д өгөгдөл оруулах',
        desc: 'C1-ээс C5 хүртэл дараалсан тоонууд бичнэ үү: <b>10, 20, 30, 40, 50</b>',
        steps: ['C1-д 10 бичнэ', 'C2-д 20, C3-д 30 гэж үргэлжлүүлнэ', 'C4-д 40, C5-д 50 бичнэ'],
        hint: '<b>Хурдан арга:</b> C1-д 10 бичиж Enter дарвал автоматаар C2 руу шилжинэ.\n<b>Tab:</b> Tab дарвал баруун нүдэнд шилжинэ.',
        xp: 10,
        check: () => {
            const vals = [10,20,30,40,50];
            let ok = true;
            vals.forEach((v,i) => { if(parseFloat(getCell(i,2).value)!==v) ok=false; });
            return { ok, msg: !ok ? 'C1-д 10, C2-д 20, C3-д 30, C4-д 40, C5-д 50 оруулна уу.' : '' };
        }
    },
    {
        id: 11, icon: '📈', title: 'AVERAGE формул',
        subtitle: 'Дундаж тооцоолох',
        desc: 'C6 нүдэнд <code>=AVERAGE(C1:C5)</code> формул оруулна уу.',
        steps: ['C6 нүдийг сонгоно', '=AVERAGE(C1:C5) бичнэ', 'Enter дарна → 30 гарна'],
        hint: '<b>AVERAGE формул:</b> <code>=AVERAGE(C1:C5)</code>\nДундаж = (10+20+30+40+50)/5 = <b>30</b>',
        xp: 15,
        check: () => {
            const c6 = getCell(5,2);
            const val = c6.formula ? parseFloat(evalFormula(c6.formula,5,2)) : parseFloat(c6.value);
            return { ok: val===30, msg: val!==30 ? `C6-д =AVERAGE(C1:C5) оруулна уу. Одоогийн: ${c6.value||c6.formula||'хоосон'}` : '' };
        }
    },
    {
        id: 12, icon: '🔼', title: 'MAX формул',
        subtitle: 'Хамгийн их утга олох',
        desc: 'C7 нүдэнд <code>=MAX(C1:C5)</code> формул оруулна уу.',
        steps: ['C7 нүдийг сонгоно', '=MAX(C1:C5) бичнэ', 'Enter → 50 гарна'],
        hint: '<b>MAX формул:</b> <code>=MAX(C1:C5)</code>\nХамгийн их утга = <b>50</b> (C5-д байгаа)',
        xp: 10,
        check: () => {
            const c7 = getCell(6,2);
            const val = c7.formula ? parseFloat(evalFormula(c7.formula,6,2)) : parseFloat(c7.value);
            return { ok: val===50, msg: val!==50 ? `C7-д =MAX(C1:C5) оруулна уу. Одоогийн: ${c7.value||c7.formula||'хоосон'}` : '' };
        }
    },
    {
        id: 13, icon: '🔽', title: 'MIN формул',
        subtitle: 'Хамгийн бага утга олох',
        desc: 'C8 нүдэнд <code>=MIN(C1:C5)</code> формул оруулна уу.',
        steps: ['C8 нүдийг сонгоно', '=MIN(C1:C5) бичнэ', 'Enter → 10 гарна'],
        hint: '<b>MIN формул:</b> <code>=MIN(C1:C5)</code>\nХамгийн бага = <b>10</b> (C1-д байгаа)',
        xp: 10,
        check: () => {
            const c8 = getCell(7,2);
            const val = c8.formula ? parseFloat(evalFormula(c8.formula,7,2)) : parseFloat(c8.value);
            return { ok: val===10, msg: val!==10 ? `C8-д =MIN(C1:C5) оруулна уу. Одоогийн: ${c8.value||c8.formula||'хоосон'}` : '' };
        }
    },
    {
        id: 14, icon: '🔢', title: 'COUNT формул',
        subtitle: 'Тоо тоолох',
        desc: 'C9 нүдэнд <code>=COUNT(C1:C5)</code> оруулна уу.',
        steps: ['C9 нүдийг сонгоно', '=COUNT(C1:C5) бичнэ', 'Enter → 5 гарна'],
        hint: '<b>COUNT формул:</b> <code>=COUNT(C1:C5)</code>\nC1:C5-д 5 тоо байгаа тул үр дүн = <b>5</b>',
        xp: 10,
        check: () => {
            const c9 = getCell(8,2);
            const val = c9.formula ? parseFloat(evalFormula(c9.formula,8,2)) : parseFloat(c9.value);
            return { ok: val===5, msg: val!==5 ? `C9-д =COUNT(C1:C5) оруулна уу. Одоогийн: ${c9.value||c9.formula||'хоосон'}` : '' };
        }
    },
    {
        id: 15, icon: '❓', title: 'IF формул',
        subtitle: 'Нөхцөлт формул',
        desc: 'D1 нүдэнд <code>=IF(B1>1000,"Их","Бага")</code> оруулна уу.',
        steps: ['D1 нүдийг сонгоно', '=IF(B1>1000,"Их","Бага") бичнэ', 'Enter → "Их" гарна (B1=1500>1000)'],
        hint: '<b>IF формул:</b> <code>=IF(нөхцөл, үнэн_утга, худал_утга)</code>\nB1=1500 > 1000 тул → <b>"Их"</b> гарна.',
        xp: 20,
        check: () => {
            const d1 = getCell(0,3);
            const val = d1.formula ? String(evalFormula(d1.formula,0,3)) : String(d1.value||'');
            const hasIF = d1.formula && d1.formula.toUpperCase().includes('IF');
            return { ok: hasIF && val==='Их', msg: !hasIF ? 'D1-д =IF(B1>1000,"Их","Бага") оруулна уу.' : (val!=='Их' ? `Үр дүн "Их" байх ёстой. Одоогийн: ${val}` : '') };
        }
    },
    {
        id: 16, icon: '📋', title: 'Нүд хуулах',
        subtitle: 'Copy & Paste хийх',
        desc: 'A1 нүдийг <b>хуулаад</b> A5 нүдэнд буулгана уу.',
        steps: ['A1 сонгоно', 'Ctrl+C (хуулах)', 'A5 нүдийг сонгоно', 'Ctrl+V (буулгах)'],
        hint: '<b>Хуулах:</b> <code>Ctrl+C</code>\n<b>Буулгах:</b> <code>Ctrl+V</code>\nА5 нүдэнд А1-ийн бүх шинж чанар хуулагдана.',
        xp: 10,
        check: () => {
            const a1 = getCell(0,0), a5 = getCell(4,0);
            const ok = String(a5.value||'').trim() === String(a1.value||'').trim() && a5.bold === a1.bold;
            return { ok, msg: !ok ? 'A1-г Ctrl+C дарж хуулаад A5-д Ctrl+V дарж буулгана уу.' : '' };
        }
    },
    {
        id: 17, icon: '📏', title: 'Мэдээлэл эрэмбэлэх',
        subtitle: 'Sort хийх',
        desc: 'E1:E5-д тоонууд бичгээд <b>Sort A→Z (өсөх)</b> хийнэ үү:\nE1=50, E2=10, E3=40, E4=20, E5=30',
        steps: ['E1-д 50, E2-д 10, E3-д 40, E4-д 20, E5-д 30 бичнэ', 'E1:E5 сонгоно', 'Data → Sort A→Z дарна'],
        hint: '<b>Эрэмбэлэх:</b> Data tab → Sort A→Z товч дарна.\n<b>Үр дүн:</b> 10, 20, 30, 40, 50 дарааллаар зогсоно.',
        xp: 15,
        check: () => {
            const vals = [0,1,2,3,4].map(r => parseFloat(getCell(r,4).value));
            const sorted = [...vals].sort((a,b)=>a-b);
            const ok = vals.every((v,i) => v===sorted[i]) && vals.length===5 && !isNaN(vals[0]);
            return { ok, msg: !ok ? 'E1:E5-д тоонууд бичгээд Sort A→Z хийнэ үү.' : '' };
        }
    },
    {
        id: 18, icon: '🎭', title: 'Conditional Format',
        subtitle: 'Нөхцөлт форматлах',
        desc: 'C1:C5 нүднүүдэд <b>Conditional Formatting</b> хэрэглэнэ үү (30-аас их тоонуудыг ногоонбаар тодруулна).',
        steps: ['C1:C5 сонгоно', 'Home → Styles → Conditional Formatting дарна', '"Highlight >0 (green)" сонгоно'],
        hint: '<b>Conditional Format:</b> Home ribbon-д байна.\n<b>Арга:</b> C1:C5 сонгоод Conditional Formatting → "Highlight >0" дарна.\n30-аас их: 40, 50 ногоон болно.',
        xp: 15,
        check: () => {
            let ok = false;
            for(let r=0;r<5;r++) {
                const c = getCell(r,2);
                if(c.fill && c.fill !== '' && parseFloat(c.value) > 0) { ok = true; break; }
            }
            return { ok, msg: !ok ? 'C1:C5 сонгоод Conditional Formatting хэрэглэнэ үү.' : '' };
        }
    },
    {
        id: 19, icon: '💬', title: 'Comment нэмэх',
        subtitle: 'Нүдэнд тайлбар бичих',
        desc: 'A1 нүдэнд <b>Comment</b> нэмж <b>"Нийт орлого"</b> гэж бичнэ үү.',
        steps: ['A1 нүдийг сонгоно', 'Insert → Comment товч дарна', '"Нийт орлого" гэж бичнэ', 'OK дарна'],
        hint: '<b>Comment нэмэх:</b> Insert tab → Comment товч\nКомент нэмсний дараа нүдэнд улаан гурвалжин тэмдэг гарна.',
        xp: 10,
        check: () => {
            const c = sheets[activeSheet].cells['A1'];
            const ok = !!c?.comment && c.comment.trim().length > 0;
            return { ok, msg: !ok ? 'A1-д comment нэмж "Нийт орлого" бичнэ үү. (Insert → Comment)' : '' };
        }
    },
    {
        id: 20, icon: '🏆', title: 'Шинэ Sheet нэмэх',
        subtitle: 'Хоёр дахь sheet үүсгэх',
        desc: '+ товч дарж <b>шинэ sheet</b> нэмэнэ үү. Шинэ sheet-д A1-д <b>"Дууслаа!"</b> гэж бичнэ үү.',
        steps: ['Доод хэсгийн + товч дарна', 'Шинэ Sheet2 үүснэ', 'A1-д "Дууслаа!" гэж бичнэ', 'Enter дарна'],
        hint: '<b>Sheet нэмэх:</b> Дэлгэцийн доод хэсэгт байгаа + товч дарна.\n<b>Шилжих:</b> Sheet tab-уудыг дарж шилжинэ.',
        xp: 20,
        check: () => {
            if(sheets.length < 2) return { ok: false, msg: '+ товч дарж шинэ sheet нэмнэ үү.' };
            let found = false;
            sheets.forEach(s => { if(String(s.cells['A1']?.value||'').trim()==='Дууслаа!') found=true; });
            return { ok: found, msg: !found ? 'Шинэ sheet-д A1-д "Дууслаа!" гэж бичнэ үү.' : '' };
        }
    }
];

// ════════════════════════════
// GAME STATE
// ════════════════════════════
let currentTask = 0; // 0-indexed
let completedTasks = new Set();
let xp = 0;
let hintVisible = false;

// ════════════════════════════
// SPREADSHEET STATE
// ════════════════════════════
let sheets = [{ name:'Sheet1', cells:{}, colWidths:{}, rowHeights:{} }];
let activeSheet = 0;
let selRow=0, selCol=0, selStartRow=0, selStartCol=0, selEndRow=0, selEndCol=0;
let editMode=false, editInput=null;
let clipboard={cells:{},rows:0,cols:0};
let undoStack=[], redoStack=[];
let currentZoom=1;
let showFormulasMode=false;

// ════════════════════════════
// CELL KEY
// ════════════════════════════
function cellKey(r,c){return COL_LETTERS[c]+(r+1);}
function parseCellKey(k){
    const m=k.match(/^([A-Z]+)(\d+)$/);
    if(!m)return null;
    const col=m[1].split('').reduce((a,c)=>a*26+c.charCodeAt(0)-64,0)-1;
    return{row:parseInt(m[2])-1,col};
}

// ════════════════════════════
// CELL DATA
// ════════════════════════════
function getCell(r,c,si){
    si=si!==undefined?si:activeSheet;
    return sheets[si].cells[cellKey(r,c)]||{};
}
function setCell(r,c,data,noHist){
    if(!noHist)saveUndo();
    const k=cellKey(r,c);
    sheets[activeSheet].cells[k]=Object.assign({},sheets[activeSheet].cells[k]||{},data);
    renderCell(r,c);updateStats();updateFormulaBar();
}
function getCellDisplay(r,c,si){
    const cd=getCell(r,c,si);
    if(!cd.value&&cd.value!==0)return'';
    const v=cd.formula?evalFormula(cd.formula,r,c):cd.value;
    if(cd.numFmt)return formatNumber(v,cd.numFmt);
    return showFormulasMode&&cd.formula?cd.formula:String(v);
}
function formatNumber(v,fmt){
    const n=parseFloat(v);
    if(isNaN(n))return v;
    switch(fmt){
        case'currency':return'$'+n.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g,',');
        case'percent':return(n*100).toFixed(1)+'%';
        case'number':return n.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g,',');
        default:return String(v);
    }
}

// ════════════════════════════
// FORMULA
// ════════════════════════════
function evalFormula(f,r,c){
    if(!f||f[0]!=='=')return f||'';
    try{return evalExpr(f.slice(1),r,c);}catch(e){return'#ERR';}
}
function evalExpr(expr,r,c){
    expr=expr.trim();
    if(/^SUM\((.+)\)$/i.test(expr)){const a=parseArgs(RegExp.$1,r,c);return a.reduce((s,b)=>s+(parseFloat(b)||0),0);}
    if(/^AVERAGE\((.+)\)$/i.test(expr)){const a=parseArgs(RegExp.$1,r,c).map(x=>parseFloat(x)).filter(x=>!isNaN(x));return a.length?a.reduce((s,b)=>s+b,0)/a.length:0;}
    if(/^MAX\((.+)\)$/i.test(expr)){const a=parseArgs(RegExp.$1,r,c).map(x=>parseFloat(x)).filter(x=>!isNaN(x));return a.length?Math.max(...a):0;}
    if(/^MIN\((.+)\)$/i.test(expr)){const a=parseArgs(RegExp.$1,r,c).map(x=>parseFloat(x)).filter(x=>!isNaN(x));return a.length?Math.min(...a):0;}
    if(/^COUNT\((.+)\)$/i.test(expr)){return parseArgs(RegExp.$1,r,c).filter(x=>!isNaN(parseFloat(x))).length;}
    if(/^COUNTA\((.+)\)$/i.test(expr)){return parseArgs(RegExp.$1,r,c).filter(x=>x!=='').length;}
    if(/^IF\((.+)\)$/i.test(expr)){
        const p=splitArgs(RegExp.$1);
        const cond=evalExpr(p[0],r,c);
        const tv=(p[1]||'TRUE').replace(/^"|"$/g,'');
        const fv=(p[2]||'FALSE').replace(/^"|"$/g,'');
        return cond?tv:fv;
    }
    if(/^CONCAT(ENATE)?\((.+)\)$/i.test(expr)){return parseArgs(RegExp.$2||RegExp.$1,r,c).join('');}
    if(/^LEN\((.+)\)$/i.test(expr)){return String(parseArgs(RegExp.$1,r,c)[0]||'').length;}
    if(/^UPPER\((.+)\)$/i.test(expr)){return String(parseArgs(RegExp.$1,r,c)[0]||'').toUpperCase();}
    if(/^LOWER\((.+)\)$/i.test(expr)){return String(parseArgs(RegExp.$1,r,c)[0]||'').toLowerCase();}
    if(/^NOW\(\)$/i.test(expr))return new Date().toLocaleString();
    if(/^TODAY\(\)$/i.test(expr))return new Date().toLocaleDateString();
    if(/^ROUND\((.+)\)$/i.test(expr)){const p=splitArgs(RegExp.$1);return+(parseFloat(evalExpr(p[0],r,c))).toFixed(parseInt(p[1]||0));}
    if(/^ABS\((.+)\)$/i.test(expr)){return Math.abs(parseFloat(parseArgs(RegExp.$1,r,c)[0])||0);}
    if(/^SQRT\((.+)\)$/i.test(expr)){return Math.sqrt(parseFloat(parseArgs(RegExp.$1,r,c)[0])||0);}
    let replaced=expr.replace(/([A-Z]+\d+)/g,m=>{
        const p=parseCellKey(m);if(!p)return m;
        const cv=getCell(p.row,p.col);
        const v=cv.formula?evalFormula(cv.formula,p.row,p.col):(cv.value||0);
        return isNaN(parseFloat(v))?`"${v}"`:v;
    });
    try{const res=Function('"use strict";return('+replaced+')')();return isFinite(res)?res:String(res);}catch(e){return'#EVAL';}
}
function expandRange(s){
    const m=s.match(/^([A-Z]+)(\d+):([A-Z]+)(\d+)$/);
    if(!m)return[s];
    const c1=COL_LETTERS.indexOf(m[1]),r1=parseInt(m[2])-1,c2=COL_LETTERS.indexOf(m[3]),r2=parseInt(m[4])-1;
    const cells=[];
    for(let r=r1;r<=r2;r++)for(let c=c1;c<=c2;c++)cells.push(cellKey(r,c));
    return cells;
}
function parseArgs(s,r,c){
    const parts=splitArgs(s),results=[];
    for(let p of parts){
        p=p.trim();
        if(/^[A-Z]+\d+:[A-Z]+\d+$/.test(p)){
            expandRange(p).forEach(k=>{const pp=parseCellKey(k);if(pp){const cv=getCell(pp.row,pp.col);results.push(cv.formula?evalFormula(cv.formula,pp.row,pp.col):(cv.value||''));}});
        }else if(/^[A-Z]+\d+$/.test(p)){
            const pp=parseCellKey(p);if(pp){const cv=getCell(pp.row,pp.col);results.push(cv.formula?evalFormula(cv.formula,pp.row,pp.col):(cv.value||''));}
        }else results.push(p.replace(/^"|"$/g,''));
    }
    return results;
}
function splitArgs(s){
    const parts=[],cur_=[];let cur='',depth=0;
    for(const ch of s){if(ch==='(')depth++;else if(ch===')')depth--;else if(ch===','&&depth===0){parts.push(cur.trim());cur='';continue;}cur+=ch;}
    if(cur.trim())parts.push(cur.trim());return parts;
}

// ════════════════════════════
// UNDO/REDO
// ════════════════════════════
function saveUndo(){undoStack.push(JSON.stringify(sheets[activeSheet].cells));if(undoStack.length>80)undoStack.shift();redoStack=[];}
function undoAction(){if(!undoStack.length)return;redoStack.push(JSON.stringify(sheets[activeSheet].cells));sheets[activeSheet].cells=JSON.parse(undoStack.pop());renderGrid();updateStats();setStatus('Undo');}
function redoAction(){if(!redoStack.length)return;undoStack.push(JSON.stringify(sheets[activeSheet].cells));sheets[activeSheet].cells=JSON.parse(redoStack.pop());renderGrid();updateStats();setStatus('Redo');}

// ════════════════════════════
// GRID RENDER
// ════════════════════════════
function renderGrid(){
    const cont=document.getElementById('gridContainer');
    cont.innerHTML='';
    const hr=document.createElement('div');hr.className='grid-header-row';
    const corner=document.createElement('div');corner.className='corner-cell';corner.innerHTML='▣';corner.onclick=()=>selectAll();hr.appendChild(corner);
    for(let c=0;c<COLS;c++){
        const ch=document.createElement('div');ch.className='col-header';ch.id='ch-'+c;
        const w=sheets[activeSheet].colWidths[c]||80;ch.style.minWidth=ch.style.width=w+'px';
        ch.textContent=COL_LETTERS[c];
        const res=document.createElement('div');res.className='col-resize';res.addEventListener('mousedown',e=>startColResize(e,c));ch.appendChild(res);
        hr.appendChild(ch);
    }
    cont.appendChild(hr);
    for(let r=0;r<ROWS;r++){
        const row=document.createElement('div');row.className='grid-row';row.id='row-'+r;
        const rh=document.createElement('div');rh.className='row-header';rh.textContent=r+1;row.appendChild(rh);
        for(let c=0;c<COLS;c++){
            const cell=document.createElement('div');cell.className='cell';cell.id='cell-'+r+'-'+c;
            const w=sheets[activeSheet].colWidths[c]||80;const h=22;
            cell.style.minWidth=cell.style.width=w+'px';cell.style.height=h+'px';
            applyCellStyles(cell,getCell(r,c));
            const span=document.createElement('span');span.textContent=getCellDisplay(r,c);cell.appendChild(span);
            if(sheets[activeSheet].cells[cellKey(r,c)]?.comment){
                const flag=document.createElement('div');flag.style.cssText='position:absolute;top:-2px;right:-2px;width:0;height:0;border-style:solid;border-width:0 7px 7px 0;border-color:transparent #e8321a transparent transparent;z-index:6;';cell.appendChild(flag);
            }
            cell.addEventListener('mousedown',e=>cellMouseDown(e,r,c));
            cell.addEventListener('dblclick',()=>startEdit(r,c));
            cell.addEventListener('contextmenu',e=>showContextMenu(e,r,c));
            row.appendChild(cell);
        }
        cont.appendChild(row);
    }
    updateSelection();
}
function applyCellStyles(el,c){
    el.style.fontWeight=c.bold?'bold':'';
    el.style.fontStyle=c.italic?'italic':'';
    el.style.textDecoration=c.underline?'underline':'';
    el.style.backgroundColor=c.fill||'';
    el.style.color=c.fontColor||'';
    el.style.fontSize=c.fontSize?c.fontSize+'px':'';
    el.style.fontFamily=c.fontFamily||'';
    el.style.justifyContent=c.align==='center'?'center':c.align==='right'?'flex-end':'';
    el.style.whiteSpace=c.wrap?'normal':'nowrap';
}
function renderCell(r,c){
    const el=document.getElementById('cell-'+r+'-'+c);if(!el)return;
    while(el.firstChild)el.removeChild(el.firstChild);
    applyCellStyles(el,getCell(r,c));
    if(el.classList.contains('editing'))return;
    const span=document.createElement('span');span.textContent=getCellDisplay(r,c);el.appendChild(span);
    if(sheets[activeSheet].cells[cellKey(r,c)]?.comment){
        const flag=document.createElement('div');flag.style.cssText='position:absolute;top:-2px;right:-2px;width:0;height:0;border-style:solid;border-width:0 7px 7px 0;border-color:transparent #e8321a transparent transparent;z-index:6;';el.appendChild(flag);
    }
}

// ════════════════════════════
// SELECTION
// ════════════════════════════
function selectCell(r,c,ext){
    if(!ext){selStartRow=selEndRow=r;selStartCol=selEndCol=c;}
    else{selEndRow=r;selEndCol=c;}
    selRow=r;selCol=c;
    updateSelection();updateFormulaBar();updateStats();
    if(editMode)stopEdit(true);
}
function cellMouseDown(e,r,c){
    if(e.button!==0)return;e.preventDefault();
    if(e.shiftKey)selectCell(r,c,true);else selectCell(r,c,false);
    const onMove=ev=>{const t=document.elementFromPoint(ev.clientX,ev.clientY);if(!t)return;const m=t.id.match(/^cell-(\d+)-(\d+)$/);if(m)selectCell(parseInt(m[1]),parseInt(m[2]),true);};
    const onUp=()=>{document.removeEventListener('mousemove',onMove);document.removeEventListener('mouseup',onUp);};
    document.addEventListener('mousemove',onMove);document.addEventListener('mouseup',onUp);
}
function updateSelection(){
    document.querySelectorAll('.cell').forEach(el=>el.classList.remove('selected','in-range'));
    const r1=Math.min(selStartRow,selEndRow),r2=Math.max(selStartRow,selEndRow);
    const c1=Math.min(selStartCol,selEndCol),c2=Math.max(selStartCol,selEndCol);
    for(let r=r1;r<=r2;r++)for(let c=c1;c<=c2;c++){
        const el=document.getElementById('cell-'+r+'-'+c);if(!el)continue;
        if(r===selRow&&c===selCol)el.classList.add('selected');else el.classList.add('in-range');
    }
    document.getElementById('nameBox').value=r1===r2&&c1===c2?cellKey(selRow,selCol):`${cellKey(r1,c1)}:${cellKey(r2,c2)}`;
}
function selectAll(){selStartRow=0;selStartCol=0;selEndRow=ROWS-1;selEndCol=COLS-1;updateSelection();}

// ════════════════════════════
// EDIT
// ════════════════════════════
function startEdit(r,c){
    if(editMode)stopEdit(true);editMode=true;selRow=r;selCol=c;
    const el=document.getElementById('cell-'+r+'-'+c);el.classList.add('editing');el.innerHTML='';
    const inp=document.createElement('input');inp.type='text';inp.className='cell-editor';
    const cd=getCell(r,c);inp.value=cd.formula||(cd.value!==undefined?cd.value:'');
    el.appendChild(inp);inp.focus();inp.select();editInput=inp;
    document.getElementById('formulaInput').value=inp.value;
    document.getElementById('sbCellMode').textContent='Edit';
    inp.addEventListener('keydown',editorKeyDown);
    inp.addEventListener('input',()=>{document.getElementById('formulaInput').value=inp.value;});
}
function stopEdit(commit){
    if(!editMode)return;editMode=false;const r=selRow,c=selCol;
    const el=document.getElementById('cell-'+r+'-'+c);el.classList.remove('editing');
    if(commit&&editInput){const raw=editInput.value;const isF=raw.startsWith('=');setCell(r,c,isF?{formula:raw,value:evalFormula(raw,r,c)}:{formula:null,value:raw});}
    editInput=null;renderCell(r,c);document.getElementById('sbCellMode').textContent='Ready';updateFormulaBar();
}
function editorKeyDown(e){
    if(e.key==='Enter'){stopEdit(true);moveCell(1,0);e.preventDefault();}
    else if(e.key==='Tab'){stopEdit(true);moveCell(0,1);e.preventDefault();}
    else if(e.key==='Escape'){stopEdit(false);renderCell(selRow,selCol);}
}
function cancelEdit(){if(editMode)stopEdit(false);}
function confirmEdit(){if(editMode)stopEdit(true);}
function formulaBarKeyDown(e){if(e.key==='Enter'){const v=document.getElementById('formulaInput').value;if(!editMode)startEdit(selRow,selCol);if(editInput)editInput.value=v;stopEdit(true);}else if(e.key==='Escape')cancelEdit();}
function formulaBarInput(){if(editMode&&editInput)editInput.value=document.getElementById('formulaInput').value;}

// ════════════════════════════
// NAV
// ════════════════════════════
function moveCell(dr,dc){
    const nr=Math.max(0,Math.min(ROWS-1,selRow+dr)),nc=Math.max(0,Math.min(COLS-1,selCol+dc));
    selectCell(nr,nc,false);
    const el=document.getElementById('cell-'+nr+'-'+nc);if(el)el.scrollIntoView({block:'nearest',inline:'nearest'});
}
document.addEventListener('keydown',e=>{
    const tag=document.activeElement.tagName;
    if(tag==='INPUT'&&document.activeElement.className==='cell-editor')return;
    if(tag==='INPUT'||tag==='SELECT'||tag==='TEXTAREA'){if(e.key==='Escape')document.activeElement.blur();return;}
    if(e.ctrlKey){
        if(e.key.toLowerCase()==='s'){e.preventDefault();saveWorkbook();}
        if(e.key.toLowerCase()==='z'){e.preventDefault();undoAction();}
        if(e.key.toLowerCase()==='y'){e.preventDefault();redoAction();}
        if(e.key.toLowerCase()==='c'){e.preventDefault();copyCell();}
        if(e.key.toLowerCase()==='x'){e.preventDefault();cutCell();}
        if(e.key.toLowerCase()==='v'){e.preventDefault();pasteCell();}
        if(e.key.toLowerCase()==='b'){e.preventDefault();toggleCellBold();}
        if(e.key.toLowerCase()==='i'){e.preventDefault();toggleCellItalic();}
        if(e.key.toLowerCase()==='u'){e.preventDefault();toggleCellUnderline();}
        if(e.key.toLowerCase()==='f'){e.preventDefault();openFindDialog();}
        if(e.key.toLowerCase()==='a'){e.preventDefault();selectAll();}
        return;
    }
    if(e.key==='ArrowUp'){e.preventDefault();if(editMode)stopEdit(true);moveCell(-1,0);}
    else if(e.key==='ArrowDown'){e.preventDefault();if(editMode)stopEdit(true);moveCell(1,0);}
    else if(e.key==='ArrowLeft'){e.preventDefault();if(editMode)stopEdit(true);moveCell(0,-1);}
    else if(e.key==='ArrowRight'){e.preventDefault();if(editMode)stopEdit(true);moveCell(0,1);}
    else if(e.key==='Tab'){e.preventDefault();if(editMode)stopEdit(true);moveCell(0,1);}
    else if(e.key==='Enter'){e.preventDefault();if(editMode)stopEdit(true);else moveCell(1,0);}
    else if(e.key==='Delete'||e.key==='Backspace')clearCells();
    else if(e.key==='F2')startEdit(selRow,selCol);
    else if(e.key.length===1&&!e.ctrlKey&&!e.metaKey){if(!editMode){startEdit(selRow,selCol);if(editInput){editInput.value=e.key;document.getElementById('formulaInput').value=e.key;}}}
});

// ════════════════════════════
// FORMAT
// ════════════════════════════
function applyToRange(prop,val){
    saveUndo();
    const r1=Math.min(selStartRow,selEndRow),r2=Math.max(selStartRow,selEndRow);
    const c1=Math.min(selStartCol,selEndCol),c2=Math.max(selStartCol,selEndCol);
    for(let r=r1;r<=r2;r++)for(let c=c1;c<=c2;c++){
        const k=cellKey(r,c);
        sheets[activeSheet].cells[k]=Object.assign({},sheets[activeSheet].cells[k]||{},{[prop]:val});
        renderCell(r,c);
    }
    updateStats();
}
function toggleCellBold(){const cur=getCell(selRow,selCol).bold;applyToRange('bold',!cur);document.getElementById('btnBold').classList.toggle('active-btn',!cur);}
function toggleCellItalic(){const cur=getCell(selRow,selCol).italic;applyToRange('italic',!cur);document.getElementById('btnItalic').classList.toggle('active-btn',!cur);}
function toggleCellUnderline(){const cur=getCell(selRow,selCol).underline;applyToRange('underline',!cur);document.getElementById('btnUnderline').classList.toggle('active-btn',!cur);}
function applyCellAlign(a){applyToRange('align',a);}
function applyCellFill(c){document.getElementById('fillColorBar').style.background=c;applyToRange('fill',c);}
function applyCellFontColor(c){document.getElementById('fontColorBar').style.background=c;applyToRange('fontColor',c);}
function applyCellFont(f){applyToRange('fontFamily',f);}
function applyCellFontSize(s){applyToRange('fontSize',parseInt(s));}
function growCellFont(){applyToRange('fontSize',(getCell(selRow,selCol).fontSize||11)+2);}
function shrinkCellFont(){applyToRange('fontSize',Math.max(6,(getCell(selRow,selCol).fontSize||11)-2));}
function toggleWrapText(){applyToRange('wrap',!getCell(selRow,selCol).wrap);}
function applyNumberFormat(fmt){document.getElementById('numFormat').value=fmt;applyToRange('numFmt',fmt);}

// ════════════════════════════
// CLIPBOARD
// ════════════════════════════
function copyCell(){
    const r1=Math.min(selStartRow,selEndRow),r2=Math.max(selStartRow,selEndRow);
    const c1=Math.min(selStartCol,selEndCol),c2=Math.max(selStartCol,selEndCol);
    clipboard={cells:{},rows:r2-r1,cols:c2-c1};
    for(let r=r1;r<=r2;r++)for(let c=c1;c<=c2;c++)clipboard.cells[`${r-r1},${c-c1}`]=Object.assign({},getCell(r,c));
    setStatus('Copied');
}
function cutCell(){
    copyCell();clipboard.cut=true;
    const r1=Math.min(selStartRow,selEndRow),r2=Math.max(selStartRow,selEndRow);
    const c1=Math.min(selStartCol,selEndCol),c2=Math.max(selStartCol,selEndCol);
    for(let r=r1;r<=r2;r++)for(let c=c1;c<=c2;c++){sheets[activeSheet].cells[cellKey(r,c)]={};renderCell(r,c);}
    setStatus('Cut');
}
function pasteCell(){
    if(!Object.keys(clipboard.cells).length)return;saveUndo();
    for(let dr=0;dr<=clipboard.rows;dr++)for(let dc=0;dc<=clipboard.cols;dc++){
        const src=clipboard.cells[`${dr},${dc}`];if(!src)continue;
        const r=selRow+dr,c=selCol+dc;if(r>=ROWS||c>=COLS)continue;
        sheets[activeSheet].cells[cellKey(r,c)]=Object.assign({},src);renderCell(r,c);
    }
    updateStats();setStatus('Pasted');
}

// ════════════════════════════
// CLEAR / FILL / SORT / MISC
// ════════════════════════════
function clearCells(){
    saveUndo();const r1=Math.min(selStartRow,selEndRow),r2=Math.max(selStartRow,selEndRow);
    const c1=Math.min(selStartCol,selEndCol),c2=Math.max(selStartCol,selEndCol);
    for(let r=r1;r<=r2;r++)for(let c=c1;c<=c2;c++){
        const k=cellKey(r,c);if(sheets[activeSheet].cells[k]){sheets[activeSheet].cells[k].value='';sheets[activeSheet].cells[k].formula=null;}renderCell(r,c);
    }
    updateStats();updateFormulaBar();
}
function fillDown(){
    saveUndo();const r1=Math.min(selStartRow,selEndRow),r2=Math.max(selStartRow,selEndRow);
    const c1=Math.min(selStartCol,selEndCol),c2=Math.max(selStartCol,selEndCol);
    for(let c=c1;c<=c2;c++){const src=Object.assign({},getCell(r1,c));for(let r=r1+1;r<=r2;r++){sheets[activeSheet].cells[cellKey(r,c)]=Object.assign({},src);renderCell(r,c);}}
    setStatus('Fill Down');
}
function autoSumSelection(){
    const r1=Math.min(selStartRow,selEndRow),r2=Math.max(selStartRow,selEndRow);
    const c1=Math.min(selStartCol,selEndCol),c2=Math.max(selStartCol,selEndCol);
    const tr=r2+1,tc=c1;const f=`=SUM(${cellKey(r1,c1)}:${cellKey(r2,c2)})`;
    setCell(tr,tc,{formula:f,value:evalFormula(f,tr,tc)});selectCell(tr,tc,false);setStatus('AutoSum done');
}
function mergeCells(){
    const r1=Math.min(selStartRow,selEndRow),r2=Math.max(selStartRow,selEndRow);
    const c1=Math.min(selStartCol,selEndCol),c2=Math.max(selStartCol,selEndCol);
    if(r1===r2&&c1===c2){setStatus('Select range to merge');return;}
    saveUndo();const val=getCell(r1,c1).value||'';
    for(let r=r1;r<=r2;r++)for(let c=c1;c<=c2;c++){sheets[activeSheet].cells[cellKey(r,c)]={value:(r===r1&&c===c1)?val:'',align:'center'};renderCell(r,c);}
    setStatus('Merged');
}
function sortAsc(){
    saveUndo();const col=selCol;const rows=[];
    for(let r=1;r<ROWS;r++){const ro={};for(let c=0;c<COLS;c++)ro[c]=Object.assign({},getCell(r,c));rows.push(ro);}
    rows.sort((a,b)=>{const va=a[col]?.value||'',vb=b[col]?.value||'';const na=parseFloat(va),nb=parseFloat(vb);if(!isNaN(na)&&!isNaN(nb))return na-nb;return String(va).localeCompare(String(vb));});
    for(let i=0;i<rows.length;i++)for(let c=0;c<COLS;c++){sheets[activeSheet].cells[cellKey(i+1,c)]=rows[i][c];renderCell(i+1,c);}
    setStatus('Sorted Ascending');
}
function sortDesc(){
    saveUndo();const col=selCol;const rows=[];
    for(let r=1;r<ROWS;r++){const ro={};for(let c=0;c<COLS;c++)ro[c]=Object.assign({},getCell(r,c));rows.push(ro);}
    rows.sort((a,b)=>{const va=a[col]?.value||'',vb=b[col]?.value||'';const na=parseFloat(va),nb=parseFloat(vb);if(!isNaN(na)&&!isNaN(nb))return nb-na;return String(vb).localeCompare(String(va));});
    for(let i=0;i<rows.length;i++)for(let c=0;c<COLS;c++){sheets[activeSheet].cells[cellKey(i+1,c)]=rows[i][c];renderCell(i+1,c);}
    setStatus('Sorted Descending');
}
function toggleAutoFilter(){setStatus('AutoFilter: Demo');}
function removeDuplicates(){
    saveUndo();const col=selCol;const seen=new Set();let removed=0;
    for(let r=0;r<ROWS;r++){const v=getCell(r,col).value;if(v===undefined||v==='')continue;if(seen.has(v)){sheets[activeSheet].cells[cellKey(r,col)]={};renderCell(r,col);removed++;}else seen.add(v);}
    setStatus(`Removed ${removed} duplicate(s)`);
}
function insertTable(){
    saveUndo();const heads=['Нэр','Утга','Ангилал'];
    heads.forEach((h,c)=>setCell(selRow,selCol+c,{value:h,bold:true,fill:'#217346',fontColor:'#fff',align:'center'},true));
    for(let r=1;r<=3;r++){setCell(selRow+r,selCol,{value:'Item '+r},true);setCell(selRow+r,selCol+1,{value:r*100},true);setCell(selRow+r,selCol+2,{value:['A','B','C'][r-1]},true);}
    renderGrid();setStatus('Table inserted');
}
function insertBarChart(){alert('📊 Тухайн сонгосон өгөгдлөөр Bar Chart үүснэ.\n(Жинхэнэ Chart: Excel программ шаардлагатай)');}
function insertLineChart(){alert('📈 Line Chart: Demo');}
function insertPieChart(){alert('🥧 Pie Chart: Demo');}
function showFormulas(){showFormulasMode=!showFormulasMode;renderGrid();setStatus(showFormulasMode?'Showing Formulas':'Showing Values');}
function evaluateFormula(){const cd=getCell(selRow,selCol);if(cd.formula)alert(`Формул: ${cd.formula}\nҮр дүн: ${evalFormula(cd.formula,selRow,selCol)}`);else setStatus('Формул байхгүй');}
function errorCheck(){let errs=[];for(const k in sheets[activeSheet].cells){const p=parseCellKey(k);if(p){const v=getCellDisplay(p.row,p.col);if(String(v).startsWith('#'))errs.push(`${k}: ${v}`);}}alert(errs.length?'Алдаа:\n'+errs.join('\n'):'Алдаа олдсонгүй!');}
function insertFunction(){const fns='SUM, AVERAGE, MAX, MIN, COUNT, IF, CONCATENATE, LEN, UPPER, LOWER, ROUND, ABS';startEdit(selRow,selCol);if(editInput){editInput.value='=';document.getElementById('formulaInput').value='=';}setStatus('Функцүүд: '+fns);}

// ════════════════════════════
// SAVE/PRINT
// ════════════════════════════
function saveWorkbook(){
    let csv='';
    for(let r=0;r<ROWS;r++){const row=[];for(let c=0;c<COLS;c++){const v=getCellDisplay(r,c);row.push(v.includes(',')?`"${v}"`:v);}csv+=row.join(',')+'\n';}
    const blob=new Blob([csv],{type:'text/csv'});const a=document.createElement('a');a.href=URL.createObjectURL(blob);a.download='Workbook1.csv';a.click();URL.revokeObjectURL(a.href);setStatus('Saved');
}
function printWorkbook(){
    const w=window.open('','_blank');let html='<html><head><title>Print</title><style>body{font-family:Calibri,sans-serif;font-size:11px;}table{border-collapse:collapse;width:100%;}td{border:1px solid #888;padding:3px 6px;}</style></head><body><table>';
    for(let r=0;r<ROWS;r++){let hd=false;for(let c=0;c<COLS;c++){if(getCell(r,c).value){hd=true;break;}}if(!hd)continue;html+='<tr>';for(let c=0;c<COLS;c++){const cd=getCell(r,c);html+=`<td>${getCellDisplay(r,c)}</td>`;}html+='</tr>';}
    html+='</table></body></html>';w.document.write(html);w.document.close();w.print();
}
function setZoom(s){currentZoom=s;document.getElementById('gridContainer').style.transform=`scale(${s})`;document.getElementById('gridContainer').style.transformOrigin='top left';document.getElementById('zoomStatus').textContent=Math.round(s*100)+'%';}
function toggleDarkMode(){document.body.classList.toggle('dark');}

// ════════════════════════════
// STATS / FORMULA BAR
// ════════════════════════════
function updateStats(){
    const r1=Math.min(selStartRow,selEndRow),r2=Math.max(selStartRow,selEndRow);
    const c1=Math.min(selStartCol,selEndCol),c2=Math.max(selStartCol,selEndCol);
    let sum=0,cnt=0;
    for(let r=r1;r<=r2;r++)for(let c=c1;c<=c2;c++){const n=parseFloat(getCellDisplay(r,c));if(!isNaN(n)){sum+=n;cnt++;}}
    const avg=cnt?(sum/cnt).toFixed(2):0;
    document.getElementById('sbSumStatus').textContent=sum;
    document.getElementById('sbAvgStatus').textContent=avg;
    document.getElementById('sbCntStatus').textContent=cnt;
}
function updateFormulaBar(){
    const cd=getCell(selRow,selCol);
    document.getElementById('formulaInput').value=cd.formula||(cd.value!==undefined?cd.value:'');
    document.getElementById('nameBox').value=cellKey(selRow,selCol);
    document.getElementById('btnBold')?.classList.toggle('active-btn',!!cd.bold);
    document.getElementById('btnItalic')?.classList.toggle('active-btn',!!cd.italic);
    if(cd.fontSize)document.getElementById('fontSize').value=cd.fontSize;
    if(cd.fontFamily)document.getElementById('fontFamily').value=cd.fontFamily;
}
function setStatus(msg){document.getElementById('statusMsg').textContent=msg;}

// ════════════════════════════
// CONTEXT / DIALOGS
// ════════════════════════════
function showContextMenu(e,r,c){e.preventDefault();selectCell(r,c,false);const m=document.getElementById('contextMenu');m.style.left=e.clientX+'px';m.style.top=e.clientY+'px';m.classList.add('show');}
document.addEventListener('click',e=>{if(!e.target.closest('#contextMenu'))document.getElementById('contextMenu').classList.remove('show');});
function closeDialog(id){document.getElementById(id).classList.remove('show');}
document.querySelectorAll('.dialog-backdrop').forEach(d=>d.addEventListener('mousedown',e=>{if(e.target===d)closeDialog(d.id);}));
function openFindDialog(){document.getElementById('findDialog').classList.add('show');}
function doFind(){const q=document.getElementById('findInput').value.toLowerCase();for(let r=0;r<ROWS;r++)for(let c=0;c<COLS;c++){if(String(getCell(r,c).value||'').toLowerCase().includes(q)){selectCell(r,c,false);return;}}alert('Олдсонгүй');}
function doReplace(){
    const q=document.getElementById('findInput').value,rep=document.getElementById('replaceInput').value;
    if(!q)return;saveUndo();let cnt=0;
    for(let r=0;r<ROWS;r++)for(let c=0;c<COLS;c++){const k=cellKey(r,c);const cd=sheets[activeSheet].cells[k];if(cd?.value&&String(cd.value).includes(q)){cd.value=String(cd.value).replaceAll(q,rep);renderCell(r,c);cnt++;}}
    setStatus(`Replaced ${cnt}`);closeDialog('findDialog');
}
function openLinkDialog(){document.getElementById('linkDialog').classList.add('show');}
function insertLinkFromDialog(){const url=document.getElementById('linkUrl').value,txt=document.getElementById('linkText').value;if(!url)return;setCell(selRow,selCol,{value:txt||url,fontColor:'#2b579a',underline:true});closeDialog('linkDialog');}
function insertComment(){document.getElementById('commentText').value=sheets[activeSheet].cells[cellKey(selRow,selCol)]?.comment||'';document.getElementById('commentDialog').classList.add('show');}
function saveComment(){const txt=document.getElementById('commentText').value;const k=cellKey(selRow,selCol);if(!sheets[activeSheet].cells[k])sheets[activeSheet].cells[k]={};sheets[activeSheet].cells[k].comment=txt;renderCell(selRow,selCol);closeDialog('commentDialog');setStatus('Comment saved');}
function startColResize(e,col){
    e.preventDefault();e.stopPropagation();const sx=e.clientX,sw=sheets[activeSheet].colWidths[col]||80;
    const onMove=ev=>{const nw=Math.max(20,sw+ev.clientX-sx);sheets[activeSheet].colWidths[col]=nw;document.querySelectorAll(`[id^="cell-"][id$="-${col}"]`).forEach(el=>{el.style.width=el.style.minWidth=nw+'px';});const ch=document.getElementById('ch-'+col);if(ch){ch.style.width=ch.style.minWidth=nw+'px';}};
    const onUp=()=>{document.removeEventListener('mousemove',onMove);document.removeEventListener('mouseup',onUp);};
    document.addEventListener('mousemove',onMove);document.addEventListener('mouseup',onUp);
}
function goToCell(ref){const p=parseCellKey(ref.trim().toUpperCase());if(p&&p.row>=0&&p.row<ROWS&&p.col>=0&&p.col<COLS){selectCell(p.row,p.col,false);}}

// ════════════════════════════
// SHEET TABS
// ════════════════════════════
function renderSheetTabs(){
    const bar=document.getElementById('sheetTabsBar');bar.innerHTML='';
    const addBtn=document.createElement('button');addBtn.className='sheet-tab-add';addBtn.textContent='+';addBtn.onclick=addSheet;addBtn.title='Add Sheet';bar.appendChild(addBtn);
    sheets.forEach((s,i)=>{
        const tab=document.createElement('div');tab.className='sheet-tab'+(i===activeSheet?' active':'');
        tab.textContent=s.name;tab.ondblclick=()=>renameSheet(i);tab.onclick=()=>switchSheet(i);bar.appendChild(tab);
    });
}
function addSheet(){sheets.push({name:'Sheet'+(sheets.length+1),cells:{},colWidths:{},rowHeights:{}});switchSheet(sheets.length-1);}
function switchSheet(i){activeSheet=i;renderGrid();renderSheetTabs();updateStats();updateFormulaBar();}
function renameSheet(i){const n=prompt('Sheet нэр:',sheets[i].name);if(n){sheets[i].name=n;renderSheetTabs();}}

// ════════════════════════════
// CONDITIONAL FORMAT
// ════════════════════════════
function showCondFormatMenu(){
    saveUndo();const r1=Math.min(selStartRow,selEndRow),r2=Math.max(selStartRow,selEndRow);
    const c1=Math.min(selStartCol,selEndCol),c2=Math.max(selStartCol,selEndCol);
    for(let r=r1;r<=r2;r++)for(let c=c1;c<=c2;c++){
        const v=parseFloat(getCell(r,c).value);if(isNaN(v))continue;
        const k=cellKey(r,c);if(!sheets[activeSheet].cells[k])sheets[activeSheet].cells[k]={};
        sheets[activeSheet].cells[k].fill=v>0?'#c6efce':'';renderCell(r,c);
    }
    setStatus('Conditional format applied');
}

// ════════════════════════════
// QUEST SYSTEM
// ════════════════════════════
function renderQuestPanel(){
    // Task list
    const listEl=document.getElementById('taskList');
    listEl.innerHTML=TASKS.map((t,i)=>{
        let cls='task-item';
        if(completedTasks.has(i))cls+=' done';
        else if(i===currentTask)cls+=' current';
        return `<div class="${cls}" onclick="jumpToTask(${i})">
            <div class="task-num">${completedTasks.has(i)?'✓':(i+1)}</div>
            <span class="task-icon">${t.icon}</span>
            <span>${t.title}</span>
            <span style="margin-left:auto;font-size:10px;color:#888;">${t.xp}XP</span>
        </div>`;
    }).join('');

    // Progress
    const done=completedTasks.size;
    const pct=Math.round(done/TASKS.length*100);
    document.getElementById('progressBar').style.width=pct+'%';
    document.getElementById('progressCurrent').textContent=done;
    document.getElementById('progressPct').textContent=pct+'%';
    document.getElementById('xpDisplay').textContent=xp;

    // Current task detail
    const t=TASKS[currentTask];
    const card=document.getElementById('currentTaskCard');
    card.innerHTML=`
        <div class="task-card-inner">
            <div class="task-card-header">
                <div class="task-card-icon">${t.icon}</div>
                <div>
                    <div class="task-card-title">Даалгавар ${t.id}: ${t.title}</div>
                    <div class="task-card-subtitle">${t.subtitle}</div>
                </div>
            </div>
            <div class="task-desc">${t.desc}</div>
            <ul class="task-steps">
                ${t.steps.map((s,i)=>`<li><div class="step-num">${i+1}</div><span>${s}</span></li>`).join('')}
            </ul>
        </div>
        <div class="hint-card">
            <div class="hint-toggle" onclick="toggleHint(this)">
                💡 Hint харах <span style="margin-left:4px;font-size:10px;">(дарна уу)</span>
            </div>
            <div class="hint-body" style="white-space:pre-line;">${t.hint}</div>
        </div>
        <button class="check-btn" onclick="checkTask()">
            🎯 Шалгах (Даалгавар ${t.id})
        </button>
        <div class="status-result" id="statusBox">
            Даалгавраа гүйцэтгээд "Шалгах" дарна уу.
        </div>
        <div class="stats-row">
            <div class="stat-pill"><div class="sv">${completedTasks.size}</div><div class="sl">Дууссан</div></div>
            <div class="stat-pill"><div class="sv">${TASKS.length - completedTasks.size}</div><div class="sl">Үлдсэн</div></div>
            <div class="stat-pill"><div class="sv">${xp}</div><div class="sl">XP</div></div>
        </div>
    `;
    // Scroll task list to current
    const curEl=listEl.querySelector('.current');if(curEl)curEl.scrollIntoView({block:'nearest'});
}

function toggleHint(el){
    const body=el.nextElementSibling;body.classList.toggle('show');
    el.innerHTML=body.classList.contains('show')?'💡 Hint нуух':'💡 Hint харах <span style="margin-left:4px;font-size:10px;">(дарна уу)</span>';
}

function jumpToTask(i){
    if(!completedTasks.has(i)&&i>currentTask){
        setStatusBox(`⚠️ Эхлээд даалгавар ${currentTask+1}-г дуусгана уу.`,'warn');return;
    }
    currentTask=i;renderQuestPanel();
}

function checkTask(){
    const t=TASKS[currentTask];
    const result=t.check();
    const box=document.getElementById('statusBox');
    if(!box)return;
    if(result.ok){
        if(!completedTasks.has(currentTask)){
            completedTasks.add(currentTask);
            xp+=t.xp;
            celebrate();
            if(currentTask<TASKS.length-1){
                setTimeout(()=>{
                    currentTask++;
                    renderQuestPanel();
                    setStatus(`✅ Даалгавар ${t.id} дууслаа! XP +${t.xp}`);
                },1800);
            }else{
                setTimeout(()=>{renderQuestPanel();allDone();},1800);
            }
        }else{
            if(currentTask<TASKS.length-1){currentTask++;renderQuestPanel();}
        }
        box.textContent=`✅ Зөв! Даалгавар ${t.id} амжилттай дуусгалаа! +${t.xp} XP`;
        box.className='status-result success';
    }else{
        box.textContent=`❌ ${result.msg}`;
        box.className='status-result error';
    }
    renderQuestPanel();
}

function setStatusBox(msg,type='warn'){
    const box=document.getElementById('statusBox');
    if(!box)return;
    box.textContent=msg;
    box.className='status-result'+(type==='success'?' success':type==='error'?' error':'');
}

function allDone(){
    const overlay=`<div style="position:fixed;inset:0;background:rgba(33,115,70,0.95);display:flex;flex-direction:column;align-items:center;justify-content:center;z-index:99999;color:#fff;">
        <div style="font-size:64px;margin-bottom:16px;">🏆</div>
        <div style="font-size:28px;font-weight:800;margin-bottom:8px;">БАЯР ХҮРГЭЕ!</div>
        <div style="font-size:16px;margin-bottom:4px;">20 даалгаварыг бүгдийг дуусгалаа!</div>
        <div style="font-size:24px;font-weight:700;margin:16px 0;">⭐ ${xp} XP</div>
        <button onclick="this.parentElement.remove()" style="background:#fff;color:#217346;border:none;border-radius:8px;padding:12px 32px;font-size:16px;font-weight:700;cursor:pointer;">Хааx</button>
    </div>`;
    document.body.insertAdjacentHTML('beforeend',overlay);
}

function celebrate(){
    const overlay=document.getElementById('celebrateOverlay');
    overlay.innerHTML='';overlay.classList.add('show');
    const colors=['#7effc0','#ffdd57','#ff6b9d','#a8d9ff','#ffb347','#c6efce'];
    for(let i=0;i<40;i++){
        const el=document.createElement('div');el.className='confetti-piece';
        el.style.left=Math.random()*100+'vw';
        el.style.top='-20px';
        el.style.background=colors[Math.floor(Math.random()*colors.length)];
        el.style.animationDelay=Math.random()*1+'s';
        el.style.animationDuration=(1.5+Math.random()*1.5)+'s';
        el.style.width=el.style.height=(6+Math.random()*10)+'px';
        el.style.borderRadius=Math.random()>0.5?'50%':'2px';
        overlay.appendChild(el);
    }
    setTimeout(()=>{overlay.innerHTML='';overlay.classList.remove('show');},3000);
}

// ════════════════════════════
// TAB SWITCHING
// ════════════════════════════
document.getElementById('tabsBar').addEventListener('click',e=>{
    const tab=e.target.closest('.tab');if(!tab)return;
    const name=tab.dataset.tab;
    document.querySelectorAll('.tab').forEach(t=>t.classList.remove('active'));tab.classList.add('active');
    document.querySelectorAll('.ribbon-panel').forEach(p=>p.classList.remove('active'));
    const panel=document.getElementById('panel-'+name);if(panel)panel.classList.add('active');
});

// ════════════════════════════
// INIT
// ════════════════════════════
renderGrid();
renderSheetTabs();
renderQuestPanel();
selectCell(0,0,false);
</script>
</body>
</html>