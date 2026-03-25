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
<title>Word Quest Game</title>
<style>
    :root {
        --word-blue: #2b579a;
        --ui: #f3f3f3;
        --line: #d4d9e2;
        --text: #222;
        --bg: #e9edf5;
        --panel: #fafafa;
        --success: #dff3e8;
        --success-border: #99d0ac;
        --warn: #fff7d6;
        --warn-border: #e0d39a;
        --danger: #ffe2e2;
        --danger-border: #e1aaaa;
        --btn-hover: #cce4f7;
        --btn-active: #bdd6f0;
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    html, body { height: 100%; overflow: hidden; }
    body { font-family: "Segoe UI", Arial, sans-serif; font-size: 12px; background: #f3f3f3; color: var(--text); display: flex; flex-direction: column; }
    body.dark { --ui: #1f2430; --line: #3a4252; --text: #edf1f7; --bg: #151922; --panel: #202633; --btn-hover: #2e3a50; }

    .title-bar { height: 52px; background: #2b579a; display: flex; align-items: center; flex-shrink: 0; position: relative; }
    .tb-left { display: flex; align-items: center; gap: 4px; padding: 0 8px; min-width: 200px; }
    .tb-qat { display: flex; gap: 2px; }
    .tb-qat button { width: 24px; height: 24px; background: transparent; border: none; color: #fff; font-size: 14px; border-radius: 3px; cursor: pointer; display: flex; align-items: center; justify-content: center; }
    .tb-qat button:hover { background: rgba(255,255,255,0.2); }
    .tb-center { position: absolute; left: 50%; transform: translateX(-50%); color: #fff; font-size: 13px; white-space: nowrap; pointer-events: none; }
    .tb-right { margin-left: auto; padding: 0 8px; }
    .tb-right button { background: #fff; border: none; color: #2b579a; padding: 4px 12px; cursor: pointer; font-size: 12px; border-radius: 4px; font-weight: 600; }

    .tabs-bar { height: 30px; background: #2b579a; display: flex; align-items: flex-end; padding: 0 2px; flex-shrink: 0; }
    .tab { height: 28px; padding: 0 14px; display: flex; align-items: center; cursor: pointer; font-size: 12px; color: rgba(255,255,255,0.85); border-radius: 4px 4px 0 0; user-select: none; white-space: nowrap; }
    .tab:hover { color: #fff; background: rgba(255,255,255,0.12); }
    .tab.active { background: #f3f3f3; color: #222; }
    body.dark .tab.active { background: var(--ui); color: var(--text); }
    .tab.file-tab { background: #217346; color: #fff; margin-right: 6px; border-radius: 0; }
    .tab.file-tab:hover { background: #1a5e38; }

    .ribbon-area { background: #f3f3f3; border-bottom: 1px solid #c8c8c8; flex-shrink: 0; min-height: 110px; overflow: hidden; }
    body.dark .ribbon-area { background: var(--ui); border-color: var(--line); }
    .ribbon-panel { display: none; }
    .ribbon-panel.active { display: flex; align-items: stretch; overflow-x: auto; }

    .file-view { display: flex; width: 100%; height: 100%; background: #fff; position: fixed; inset: 0; z-index: 500; }
    .file-sidebar { width: 200px; background: #217346; color: #fff; padding: 20px 0; flex-shrink: 0; }
    .file-sidebar .fs-title { font-size: 20px; padding: 0 20px 16px; border-bottom: 1px solid rgba(255,255,255,0.2); margin-bottom: 8px; }
    .file-item { padding: 10px 20px; cursor: pointer; font-size: 13px; display: flex; align-items: center; gap: 10px; }
    .file-item:hover { background: rgba(255,255,255,0.15); }
    .file-main { flex: 1; padding: 24px; overflow: auto; background: #fafafa; }
    .file-close { position: absolute; top: 10px; right: 14px; font-size: 20px; cursor: pointer; color: #555; background: none; border: none; }

    .rgroup { display: flex; flex-direction: column; align-items: flex-start; border-right: 1px solid #d4d4d4; padding: 4px 8px 18px; min-width: fit-content; position: relative; gap: 2px; }
    body.dark .rgroup { border-color: var(--line); }
    .rgroup:last-child { border-right: none; }
    .rgroup-title { position: absolute; bottom: 3px; left: 0; right: 0; text-align: center; font-size: 10px; color: #666; }
    .rgroup-body { display: flex; align-items: flex-start; gap: 2px; flex: 1; }

    .rb { display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 2px; padding: 2px 6px; background: transparent; border: 1px solid transparent; border-radius: 3px; cursor: pointer; color: var(--text); font-size: 11px; line-height: 1.2; min-width: 36px; white-space: nowrap; font-family: "Segoe UI", Arial, sans-serif; }
    .rb:hover { background: var(--btn-hover); border-color: #90bce8; }
    .rb .ico { font-size: 24px; line-height: 1; }
    .rb .ico-sm { font-size: 16px; line-height: 1; }
    .rb.large { height: 86px; padding: 4px 8px; min-width: 48px; }
    .rb.large .ico { font-size: 32px; }
    .rb.small { flex-direction: row; height: 22px; gap: 4px; padding: 1px 6px; min-width: 60px; justify-content: flex-start; }
    .rb-col { display: flex; flex-direction: column; gap: 1px; }
    .rb-row { display: flex; align-items: center; gap: 2px; }

    .rsel { height: 22px; border: 1px solid #c8c8c8; background: #fff; font-size: 11px; border-radius: 2px; color: #222; padding: 0 4px; cursor: pointer; }
    body.dark .rsel { background: #273043; color: #fff; border-color: #3c4760; }

    .fmt-btn { width: 24px; height: 22px; background: transparent; border: 1px solid transparent; border-radius: 2px; cursor: pointer; font-size: 12px; color: var(--text); display: flex; align-items: center; justify-content: center; font-family: "Segoe UI", Arial, sans-serif; }
    .fmt-btn:hover { background: var(--btn-hover); border-color: #90bce8; }
    .fmt-btn.wide { width: auto; padding: 0 6px; font-size: 11px; }

    .color-btn-wrap { position: relative; display: flex; flex-direction: column; align-items: center; }
    .color-btn { width: 24px; height: 22px; background: transparent; border: 1px solid transparent; border-radius: 2px; cursor: pointer; display: flex; flex-direction: column; align-items: center; justify-content: center; font-size: 12px; font-weight: 700; color: var(--text); }
    .color-btn:hover { background: var(--btn-hover); border-color: #90bce8; }
    .color-bar { width: 18px; height: 4px; border-radius: 1px; }
    .rsep { width: 1px; background: #d4d4d4; margin: 2px 4px; align-self: stretch; }

    .style-gallery { display: flex; gap: 2px; align-items: center; }
    .style-item { padding: 2px 8px; min-width: 68px; height: 60px; border: 1px solid transparent; border-radius: 2px; cursor: pointer; font-size: 11px; text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: center; }
    .style-item:hover { border-color: #90bce8; background: var(--btn-hover); }
    .si-label { font-size: 9px; color: #666; margin-top: 2px; }

    .ruler-wrap { background: #f3f3f3; border-bottom: 1px solid #d0d0d0; height: 20px; display: flex; align-items: center; padding: 0 4px; flex-shrink: 0; }
    body.dark .ruler-wrap { background: #1d2430; border-color: var(--line); }
    .ruler-wrap.hidden { display: none; }
    .ruler-inner { height: 100%; flex: 1; background: repeating-linear-gradient(to right, transparent 0px, transparent 9px, #bbb 9px, #bbb 10px); }

    .workspace { display: grid; grid-template-columns: 1fr 340px; flex: 1; overflow: hidden; min-height: 0; }
    .editor-wrap { overflow: auto; background: var(--bg); padding: 20px 24px 40px; border-right: 1px solid var(--line); }
    .page { width: 850px; min-height: 1100px; margin: 0 auto; background: #fff; color: #222; box-shadow: 0 2px 12px rgba(0,0,0,0.15); padding: 90px 80px; outline: none; line-height: 1.6; font-size: 16px; transform-origin: top center; }
    .page p { margin: 0 0 14px; }
    .page.grid-on { background-image: linear-gradient(to bottom,rgba(0,0,0,.04) 1px,transparent 1px),linear-gradient(to right,rgba(0,0,0,.03) 1px,transparent 1px); background-size: 100% 28px, 28px 100%; }
    .header-zone, .footer-zone { color: #8a8a8a; font-size: 13px; border: 1px dashed #c7cfdb; padding: 8px 12px; margin-bottom: 18px; border-radius: 4px; background: #fafcff; }
    .footer-zone { margin-top: 22px; margin-bottom: 0; }
    table.simple-table { border-collapse: collapse; width: 100%; margin: 14px 0; }
    table.simple-table td, table.simple-table th { border: 1px solid #777; padding: 8px; }

    .side-panel { background: var(--panel); padding: 12px; overflow-y: auto; display: flex; flex-direction: column; gap: 10px; }

    .card { background: #fff; border: 1px solid #ddd; border-radius: 10px; padding: 12px; box-shadow: 0 1px 4px rgba(0,0,0,0.06); }
    body.dark .card { background: #2a3243; border-color: #44506a; color: #fff; }

    .progress-label { display: flex; justify-content: space-between; font-size: 11px; color: #666; margin-bottom: 4px; }
    .progress-track { height: 10px; background: #e0e6f0; border-radius: 99px; overflow: hidden; margin-bottom: 8px; }
    .progress-fill { height: 100%; background: linear-gradient(90deg,#2b579a,#4a9fd4); border-radius: 99px; transition: width 0.5s ease; }
    .progress-stats { display: flex; justify-content: space-between; font-size: 12px; }

    .quest-header { display: flex; align-items: center; gap: 8px; margin-bottom: 8px; }
    .quest-num { width: 26px; height: 26px; background: #2b579a; color: #fff; border-radius: 50%; font-size: 12px; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .quest-title { font-size: 14px; font-weight: 700; }
    .quest-desc { font-size: 12px; line-height: 1.7; color: #333; margin-bottom: 10px; white-space: pre-line; }
    body.dark .quest-desc { color: #ccc; }

    .req-list { list-style: none; margin-bottom: 10px; }
    .req-item { display: flex; align-items: center; gap: 7px; padding: 3px 0; font-size: 12px; }
    .req-dot { width: 8px; height: 8px; border-radius: 50%; background: #ccc; flex-shrink: 0; transition: background 0.3s; }
    .req-dot.ok   { background: #27ae60; }
    .req-dot.fail { background: #e74c3c; }

    .hint-toggle { display: flex; align-items: center; gap: 5px; cursor: pointer; font-size: 12px; color: #2b579a; background: none; border: none; padding: 3px 0; font-family: inherit; }
    .hint-toggle:hover { text-decoration: underline; }
    .hint-body { display: none; background: #fffbea; border: 1px solid #f0d96d; border-radius: 8px; padding: 10px 12px; font-size: 12px; line-height: 1.75; color: #5a4800; white-space: pre-line; margin: 5px 0; }
    .hint-body.open { display: block; }

    .status-box { padding: 9px 12px; border-radius: 8px; border: 1px solid var(--warn-border); background: var(--warn); font-size: 12px; line-height: 1.5; color: #222; margin-top: 8px; }

    .check-btn { width: 100%; height: 40px; border: 0; background: linear-gradient(135deg,#2b579a,#4a9fd4); color: #fff; border-radius: 8px; font-size: 14px; font-weight: 700; cursor: pointer; margin-top: 8px; transition: transform 0.1s, box-shadow 0.1s; box-shadow: 0 4px 12px rgba(43,87,154,0.3); }
    .check-btn:hover { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(43,87,154,0.4); }
    .check-btn:active { transform: translateY(0); }

    .success-flash { position: fixed; inset: 0; pointer-events: none; z-index: 9999; display: flex; align-items: center; justify-content: center; opacity: 0; transition: opacity 0.25s; }
    .success-flash.show { opacity: 1; }
    .sf-inner { background: linear-gradient(135deg,#1a6f3c,#27ae60); color: #fff; border-radius: 20px; padding: 28px 48px; text-align: center; box-shadow: 0 20px 60px rgba(0,0,0,0.4); transform: scale(0.7); transition: transform 0.25s; }
    .success-flash.show .sf-inner { transform: scale(1); }
    .sf-emoji { font-size: 52px; margin-bottom: 8px; }
    .sf-title { font-size: 22px; font-weight: 700; }

    .status-bar { height: 26px; background: #2b579a; color: #fff; display: flex; align-items: center; justify-content: space-between; padding: 0 10px; font-size: 12px; flex-shrink: 0; }
    .sb-left { display: flex; gap: 14px; align-items: center; }
    .sb-right { display: flex; gap: 10px; align-items: center; }
    .sb-sep { width: 1px; height: 14px; background: rgba(255,255,255,0.3); }

    .dialog-backdrop { position: fixed; inset: 0; background: rgba(0,0,0,0.35); display: none; align-items: center; justify-content: center; z-index: 999; }
    .dialog-backdrop.show { display: flex; }
    .dialog { width: 420px; max-width: 92vw; background: #fff; border-radius: 8px; box-shadow: 0 8px 32px rgba(0,0,0,0.22); padding: 20px; color: #222; }
    .dialog h3 { margin-bottom: 14px; }
    .form-row { margin-bottom: 10px; display: flex; flex-direction: column; gap: 4px; }
    .form-row label { font-size: 12px; color: #555; }
    .form-row input { height: 28px; border: 1px solid #ccc; border-radius: 4px; padding: 0 8px; font-size: 13px; }
    .d-actions { display: flex; justify-content: flex-end; gap: 8px; margin-top: 12px; }
    .btn-ok { background: #2b579a; color: #fff; border: none; border-radius: 4px; padding: 6px 16px; cursor: pointer; font-size: 12px; }
    .btn-ok:hover { background: #1f4377; }
    .btn-cancel { background: #fff; border: 1px solid #ccc; border-radius: 4px; padding: 6px 16px; cursor: pointer; font-size: 12px; }
    .btn-cancel:hover { background: #f3f3f3; }
    .hidden { display: none !important; }
</style>
</head>
<body>

<div class="success-flash" id="successFlash">
    <div class="sf-inner">
        <div class="sf-emoji" id="sfEmoji">🎉</div>
        <div class="sf-title" id="sfTitle">Зөв!</div>
    </div>
</div>

<!-- TITLE BAR -->
<div class="title-bar">
    <div class="tb-left">
        <svg width="18" height="18" viewBox="0 0 18 18" style="flex-shrink:0"><rect width="18" height="18" rx="2" fill="#fff"/><text x="2" y="14" font-size="13" font-weight="700" fill="#2b579a">W</text></svg>
        <div class="tb-qat">
            <button onclick="saveDocument()" title="Хадгалах (Ctrl+S)">💾</button>
            <button onclick="undoAction()" title="Буцаах (Ctrl+Z)">↶</button>
            <button onclick="redoAction()" title="Дахин хийх (Ctrl+Y)">↷</button>
            <button onclick="printDocument()">🖨️</button>
        </div>
    </div>
    <div class="tb-center">Document1.html — Word Quest</div>
    <div class="tb-right"><button>⬆ Share</button></div>
</div>

<!-- TABS -->
<div class="tabs-bar" id="tabsBar">
    <div class="tab file-tab" data-tab="file">File</div>
    <div class="tab active" data-tab="home">Home</div>
    <div class="tab" data-tab="insert">Insert</div>
    <div class="tab" data-tab="design">Design</div>
    <div class="tab" data-tab="layout">Layout</div>
    <div class="tab" data-tab="review">Review</div>
    <div class="tab" data-tab="view">View</div>
</div>

<!-- RIBBON -->
<div class="ribbon-area">

  <!-- FILE OVERLAY -->
  <div class="ribbon-panel" id="panel-file"></div>
  <div id="fileOverlay" class="file-view hidden">
    <button class="file-close" onclick="closeFilePanel()">✕</button>
    <div class="file-sidebar">
      <div class="fs-title">File</div>
      <div class="file-item" onclick="newDocument()">🆕 Шинэ</div>
      <div class="file-item" onclick="saveDocument()">💾 Хадгалах</div>
      <div class="file-item" onclick="printDocument()">🖨️ Хэвлэх</div>
    </div>
    <div class="file-main"><h2>Файл</h2></div>
  </div>

  <!-- HOME -->
  <div class="ribbon-panel active" id="panel-home">
    <div class="rgroup">
      <div class="rgroup-body">
        <div class="rb-col">
          <button class="rb large" onclick="pasteText()"><span class="ico">📋</span>Paste</button>
        </div>
        <div class="rb-col">
          <button class="rb small" onclick="cutText()"><span class="ico-sm">✂️</span>Cut</button>
          <button class="rb small" onclick="copyText()"><span class="ico-sm">📄</span>Copy</button>
        </div>
      </div>
      <div class="rgroup-title">Clipboard</div>
    </div>
    <div class="rgroup">
      <div class="rgroup-body">
        <div class="rb-col" style="gap:3px;">
          <div class="rb-row">
            <select id="fontFamily" class="rsel" style="width:140px;" onchange="execCmd('fontName',this.value)">
              <option value="Arial">Arial</option><option value="Calibri">Calibri</option>
              <option value="Times New Roman">Times New Roman</option><option value="Verdana">Verdana</option>
              <option value="Georgia">Georgia</option><option value="Courier New">Courier New</option>
            </select>
            <select id="fontSize" class="rsel" style="width:44px;" onchange="applyFontSize(this.value)">
              <option value="1">8</option><option value="2">10</option><option value="3" selected>12</option>
              <option value="4">14</option><option value="5">18</option><option value="6">24</option><option value="7">36</option>
            </select>
            <button class="fmt-btn wide" onclick="growFont()">A▲</button>
            <button class="fmt-btn wide" onclick="shrinkFont()">A▼</button>
            <button class="fmt-btn wide" onclick="clearFormatting()">Aa✕</button>
          </div>
          <div class="rb-row">
            <button class="fmt-btn" onclick="execCmd('bold')" title="Тод (Ctrl+B)"><b>B</b></button>
            <button class="fmt-btn" onclick="execCmd('italic')" title="Налуу (Ctrl+I)"><i>I</i></button>
            <button class="fmt-btn" onclick="execCmd('underline')" title="Зураас (Ctrl+U)" style="text-decoration:underline;">U</button>
            <button class="fmt-btn" onclick="execCmd('strikeThrough')"><s>S</s></button>
            <button class="fmt-btn" onclick="execCmd('subscript')">X₂</button>
            <button class="fmt-btn" onclick="execCmd('superscript')">X²</button>
            <div class="rsep"></div>
            <div class="color-btn-wrap" title="Үсгийн өнгө">
              <button class="color-btn" onclick="document.getElementById('tcInput').click()">
                <span style="font-weight:700;color:#e8000d;">A</span>
                <div class="color-bar" id="tcBar" style="background:#e8000d;"></div>
              </button>
              <input type="color" id="tcInput" value="#e8000d" onchange="applyTextColor(this.value)" style="position:absolute;opacity:0;width:0;height:0;">
            </div>
            <div class="color-btn-wrap" title="Тодруулах">
              <button class="color-btn" onclick="document.getElementById('hlInput').click()">
                <span style="font-size:11px;background:#ffff00;padding:0 1px;">ab</span>
                <div class="color-bar" id="hlBar" style="background:#ffff00;"></div>
              </button>
              <input type="color" id="hlInput" value="#ffff00" onchange="applyHighlight(this.value)" style="position:absolute;opacity:0;width:0;height:0;">
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
            <button class="fmt-btn wide" onclick="execCmd('insertUnorderedList')" title="Цэгтэй жагсаалт">☰•</button>
            <button class="fmt-btn wide" onclick="execCmd('insertOrderedList')" title="Дугаарлагдсан">1≡</button>
            <button class="fmt-btn wide" onclick="execCmd('outdent')">⇤</button>
            <button class="fmt-btn wide" onclick="execCmd('indent')">⇥</button>
          </div>
          <div class="rb-row">
            <button class="fmt-btn wide" onclick="execCmd('justifyLeft')" title="Зүүн (Ctrl+L)">⬅≡</button>
            <button class="fmt-btn wide" onclick="execCmd('justifyCenter')" title="Дунд (Ctrl+E)">≡≡</button>
            <button class="fmt-btn wide" onclick="execCmd('justifyRight')" title="Баруун (Ctrl+R)">≡⬅</button>
            <button class="fmt-btn wide" onclick="execCmd('justifyFull')" title="Жигд (Ctrl+J)">⬅≡⬅</button>
            <div class="rsep"></div>
            <div class="color-btn-wrap">
              <button class="color-btn" onclick="document.getElementById('bgInput').click()"><span>⬛</span><div class="color-bar" id="bgBar" style="background:#ffff00;"></div></button>
              <input type="color" id="bgInput" value="#ffff00" onchange="execCmd('backColor',this.value)" style="position:absolute;opacity:0;width:0;height:0;">
            </div>
          </div>
        </div>
      </div>
      <div class="rgroup-title">Paragraph</div>
    </div>
    <div class="rgroup">
      <div class="rgroup-body">
        <div class="style-gallery">
          <div class="style-item" onclick="applyStyle('normal')"><div style="font-size:13px;">AaBbCcDd</div><div class="si-label">¶ Normal</div></div>
          <div class="style-item" onclick="applyStyle('heading1')"><div style="font-size:14px;color:#2b579a;font-weight:600;">AaBbC</div><div class="si-label">¶ Heading 1</div></div>
          <div class="style-item" onclick="applyStyle('heading2')"><div style="font-size:13px;color:#2b579a;">AaBbCc</div><div class="si-label">¶ Heading 2</div></div>
          <div class="style-item" onclick="applyStyle('title')"><div style="font-size:17px;color:#222;">Aat</div><div class="si-label">¶ Title</div></div>
          <div class="style-item" onclick="applyStyle('subtitle')"><div style="font-size:12px;color:#666;">AaBbCcD</div><div class="si-label">¶ Subtitle</div></div>
        </div>
      </div>
      <div class="rgroup-title">Styles</div>
    </div>
    <div class="rgroup">
      <div class="rgroup-body">
        <div class="rb-col">
          <button class="rb small" onclick="openFindDialog()"><span class="ico-sm">🔍</span>Find</button>
          <button class="rb small" onclick="openFindDialog()"><span class="ico-sm">↔️</span>Replace</button>
          <button class="rb small" onclick="selectAllText()"><span class="ico-sm">⬛</span>Select All</button>
        </div>
      </div>
      <div class="rgroup-title">Editing</div>
    </div>
    <div class="rgroup">
      <div class="rgroup-body">
        <button class="rb large" onclick="checkTask()" style="background:#2b579a;color:#fff;border-radius:6px;"><span class="ico">🎯</span>Шалгах</button>
      </div>
      <div class="rgroup-title">Quest</div>
    </div>
  </div>

  <!-- INSERT -->
  <div class="ribbon-panel" id="panel-insert">
    <div class="rgroup">
      <div class="rgroup-body">
        <div class="rb-col">
          <button class="rb small" onclick="insertPageBreak()"><span class="ico-sm">⬛</span>Page Break</button>
          <button class="rb small" onclick="insertCoverPage()"><span class="ico-sm">📄</span>Cover Page</button>
        </div>
      </div>
      <div class="rgroup-title">Pages</div>
    </div>
    <div class="rgroup">
      <div class="rgroup-body">
        <button class="rb large" onclick="insertTable()"><span class="ico">⊞</span>Table</button>
      </div>
      <div class="rgroup-title">Tables</div>
    </div>
    <div class="rgroup">
      <div class="rgroup-body">
        <div class="rb-row">
          <button class="rb" onclick="insertImageByUrl()"><span class="ico-sm">🖼️</span><span>Pictures</span></button>
          <button class="rb" onclick="insertShape()"><span class="ico-sm">🔷</span><span>Shapes</span></button>
        </div>
      </div>
      <div class="rgroup-title">Illustrations</div>
    </div>
    <div class="rgroup">
      <div class="rgroup-body">
        <div class="rb-col">
          <button class="rb small" onclick="openLinkDialog()"><span class="ico-sm">🔗</span>Link</button>
          <button class="rb small" onclick="insertBookmark()"><span class="ico-sm">🔖</span>Bookmark</button>
        </div>
      </div>
      <div class="rgroup-title">Links</div>
    </div>
    <div class="rgroup">
      <div class="rgroup-body">
        <div class="rb-col">
          <button class="rb small" onclick="insertHeader()"><span class="ico-sm">⬆️</span>Header</button>
          <button class="rb small" onclick="insertFooter()"><span class="ico-sm">⬇️</span>Footer</button>
          <button class="rb small" onclick="insertPageNumber()"><span class="ico-sm">#</span>Page Number</button>
        </div>
      </div>
      <div class="rgroup-title">Header & Footer</div>
    </div>
    <div class="rgroup">
      <div class="rgroup-body">
        <div class="rb-col">
          <button class="rb small" onclick="insertWordArt()"><span class="ico-sm">✨</span>WordArt</button>
          <button class="rb small" onclick="insertDate()"><span class="ico-sm">📅</span>Date & Time</button>
          <button class="rb small" onclick="insertTextBox()"><span class="ico-sm">🔤</span>Text Box</button>
          <button class="rb small" onclick="insertComment()"><span class="ico-sm">💬</span>Comment</button>
        </div>
      </div>
      <div class="rgroup-title">Text / Other</div>
    </div>
    <div class="rgroup">
      <div class="rgroup-body">
        <div class="rb-col">
          <button class="rb small" onclick="insertEquation()"><span class="ico-sm">∑</span>Equation</button>
          <button class="rb small" onclick="insertSymbol()"><span class="ico-sm">Ω</span>Symbol</button>
        </div>
      </div>
      <div class="rgroup-title">Symbols</div>
    </div>
  </div>

  <!-- DESIGN -->
  <div class="ribbon-panel" id="panel-design">
    <div class="rgroup">
      <div class="rgroup-body">
        <div class="rb-col">
          <button class="rb small" onclick="setTheme('#fff','#222')"><span class="ico-sm">⬜</span>Office</button>
          <button class="rb small" onclick="setTheme('#fff8e7','#222')"><span class="ico-sm">🟡</span>Warm</button>
          <button class="rb small" onclick="setTheme('#f0f8ff','#222')"><span class="ico-sm">🔵</span>Cool Blue</button>
          <button class="rb small" onclick="setTheme('#1a1a2e','#eee')"><span class="ico-sm">⚫</span>Dark</button>
        </div>
      </div>
      <div class="rgroup-title">Themes</div>
    </div>
    <div class="rgroup">
      <div class="rgroup-body">
        <div class="rb-col">
          <button class="rb small" onclick="toggleWatermark()"><span class="ico-sm">🔏</span>Watermark</button>
          <button class="rb small" onclick="showPageColor()"><span class="ico-sm">🎨</span>Page Color</button>
          <button class="rb small" onclick="togglePageBorder()"><span class="ico-sm">⬛</span>Page Borders</button>
        </div>
      </div>
      <div class="rgroup-title">Page Background</div>
    </div>
  </div>

  <!-- LAYOUT -->
  <div class="ribbon-panel" id="panel-layout">
    <div class="rgroup">
      <div class="rgroup-body">
        <div class="rb-col">
          <button class="rb small" onclick="setMargins('normal')"><span class="ico-sm">⬜</span>Margins</button>
          <button class="rb small" onclick="setOrientation('portrait')"><span class="ico-sm">📄</span>Portrait</button>
          <button class="rb small" onclick="setOrientation('landscape')"><span class="ico-sm">📄</span>Landscape</button>
        </div>
      </div>
      <div class="rgroup-title">Page Setup</div>
    </div>
  </div>

  <!-- REVIEW -->
  <div class="ribbon-panel" id="panel-review">
    <div class="rgroup">
      <div class="rgroup-body">
        <div class="rb-row">
          <button class="rb" onclick="spellCheck()"><span class="ico-sm">abc✓</span><span>Spelling</span></button>
          <button class="rb" onclick="showWordCount()"><span class="ico-sm">123</span><span>Word Count</span></button>
        </div>
      </div>
      <div class="rgroup-title">Proofing</div>
    </div>
    <div class="rgroup">
      <div class="rgroup-body">
        <button class="rb large" onclick="readAloud()"><span class="ico">🔊</span>Read Aloud</button>
      </div>
      <div class="rgroup-title">Speech</div>
    </div>
    <div class="rgroup">
      <div class="rgroup-body">
        <button class="rb large" onclick="insertComment()"><span class="ico">💬</span>Comment</button>
      </div>
      <div class="rgroup-title">Comments</div>
    </div>
  </div>

  <!-- VIEW -->
  <div class="ribbon-panel" id="panel-view">
    <div class="rgroup">
      <div class="rgroup-body">
        <div class="rb-row">
          <button class="rb" onclick="setZoom(0.75)"><span class="ico-sm">🔍</span><span>75%</span></button>
          <button class="rb" onclick="setZoom(1)"><span class="ico-sm">🔍</span><span>100%</span></button>
          <button class="rb" onclick="setZoom(1.25)"><span class="ico-sm">🔎</span><span>125%</span></button>
        </div>
      </div>
      <div class="rgroup-title">Zoom</div>
    </div>
    <div class="rgroup">
      <div class="rgroup-body">
        <div class="rb-col" style="gap:5px;">
          <label style="display:flex;align-items:center;gap:5px;font-size:11px;cursor:pointer;"><input type="checkbox" id="chkRuler" checked onchange="toggleRuler()"> Ruler</label>
          <label style="display:flex;align-items:center;gap:5px;font-size:11px;cursor:pointer;"><input type="checkbox" id="chkGrid" onchange="toggleGrid()"> Gridlines</label>
        </div>
      </div>
      <div class="rgroup-title">Show</div>
    </div>
    <div class="rgroup">
      <div class="rgroup-body">
        <button class="rb large" onclick="toggleDarkMode()"><span class="ico">🌙</span>Dark Mode</button>
      </div>
      <div class="rgroup-title">Theme</div>
    </div>
  </div>

</div><!-- /ribbon-area -->

<div class="ruler-wrap" id="rulerWrap"><div class="ruler-inner"></div></div>

<!-- WORKSPACE -->
<div class="workspace">
  <div class="editor-wrap">
    <div id="editor" class="page" contenteditable="true">
      <p id="ln1"><p>Сайн байна уу </p></p>
      <p id="ln2">Монгол хэлний анхдугаар хичээл.</p>
      <p id="ln3">Word программ нь олон боломжтой.</p>
      <p id="ln4">Текстийг засаж форматлаж болно.</p>
    
    </div>
  </div>

  <div class="side-panel">

    <!-- PROGRESS -->
    <div class="card">
      <div class="progress-label"><span>🏆 Явц</span><span id="progText">0 / 20</span></div>
      <div class="progress-track"><div class="progress-fill" id="progFill" style="width:0%"></div></div>
      <div class="progress-stats">
        <span>XP: <b id="xpLbl">0</b></span>
        <span>Streak: <b id="strkLbl">0</b>🔥</span>
        <span>Түвшин: <b id="lvlLbl">1</b></span>
      </div>
    </div>

    <!-- QUEST CARD -->
    <div class="card" id="questCard">
      <div class="quest-header">
        <div class="quest-num" id="qNum">1</div>
        <div class="quest-title" id="qTitle">—</div>
      </div>
      <div class="quest-desc" id="qDesc"></div>
      <ul class="req-list" id="qReqs"></ul>
      <button class="hint-toggle" onclick="toggleHint()" id="hintBtn">💡 Hint харах</button>
      <div class="hint-body" id="hintBody"></div>
      <button class="check-btn" onclick="checkTask()">🎯 Шалгах</button>
      <div class="status-box" id="statusBox">Даалгавраа хийж "Шалгах" товчийг дарна уу.</div>
    </div>

    <!-- STATS -->
    <div class="card">
      <div style="font-size:12px;line-height:1.9;">
        <div>📝 Үг: <b id="wcLive">0</b></div>
        <div>🔤 Тэмдэгт: <b id="ccLive">0</b></div>
        <div>🔍 Zoom: <b id="zLbl">100%</b></div>
      </div>
    </div>

  </div>
</div>

<!-- STATUS BAR -->
<div class="status-bar">
  <div class="sb-left">
    <span id="sbLeft">Бэлэн</span>
    <div class="sb-sep"></div>
    <span id="sbWords">0 үг</span>
    <div class="sb-sep"></div>
    <span>Монгол</span>
  </div>
  <div class="sb-right">
    <span><?= htmlspecialchars($_SESSION['username']) ?></span>
    <div class="sb-sep"></div>
    <span>XP: <b id="sbXp">0</b></span>
    <div class="sb-sep"></div>
    <button onclick="setZoom(0.9)" style="background:transparent;border:none;color:#fff;cursor:pointer;font-size:11px;">90%</button>
    <button onclick="setZoom(1)"   style="background:transparent;border:none;color:#fff;cursor:pointer;font-size:11px;">100%</button>
    <button onclick="setZoom(1.25)" style="background:transparent;border:none;color:#fff;cursor:pointer;font-size:11px;">125%</button>
    <span id="sbZoom">100%</span>
  </div>
</div>

<!-- DIALOGS -->
<div class="dialog-backdrop" id="linkDlg">
  <div class="dialog">
    <h3>🔗 Холбоос оруулах</h3>
    <div class="form-row"><label>URL хаяг</label><input type="text" id="lUrl" placeholder="https://example.com"></div>
    <div class="form-row"><label>Харуулах текст</label><input type="text" id="lText" placeholder="Холбоосын текст"></div>
    <div class="d-actions">
      <button class="btn-cancel" onclick="closeDlg('linkDlg')">Болих</button>
      <button class="btn-ok" onclick="doInsertLink()">Оруулах</button>
    </div>
  </div>
</div>

<div class="dialog-backdrop" id="findDlg">
  <div class="dialog">
    <h3>🔍 Хайх / Солих</h3>
    <div class="form-row"><label>Хайх</label><input type="text" id="fFind" placeholder="Хайх текст..."></div>
    <div class="form-row"><label>Солих</label><input type="text" id="fRepl" placeholder="Солих текст..."></div>
    <div class="d-actions">
      <button class="btn-cancel" onclick="closeDlg('findDlg')">Болих</button>
      <button class="btn-ok" onclick="doFind()">Хайх</button>
      <button class="btn-ok" onclick="doReplace()">Бүгдийг солих</button>
    </div>
  </div>
</div>

<div class="dialog-backdrop" id="allDoneDlg">
  <div class="dialog" style="text-align:center;">
    <div style="font-size:60px;margin-bottom:10px;">🏆</div>
    <h3 style="font-size:22px;margin-bottom:8px;">Бүх 20 даалгавар дууслаа!</h3>
    <p style="font-size:14px;color:#555;margin-bottom:16px;">Word Quest-ийг амжилттай дүүргэлээ!<br>Нийт XP: <b id="finalXp">0</b></p>
    <button class="btn-ok" onclick="restartGame()" style="font-size:14px;padding:10px 28px;">🔄 Дахин тоглох</button>
  </div>
</div>

<script>
// ════════════════════════════════════════
// 20 QUESTS  –  check() → boolean[]
// ════════════════════════════════════════
const QUESTS = [

/* 1 */ {
  title: "Эхний мөрийг Bold хийх",
  desc:  "«Гарчиг» үгийг сонгоод Bold (тод) форматтай болго.",
  reqs:  ["«Гарчиг» текст байх", "Тэр текст Bold форматтай байх"],
  hint:  "1. «Гарчиг» үгийг хулгана дарж чирч сонго.\n2. Home → Font → «B» товч дар  (эсвэл Ctrl+B).",
  check(ed) {
    // «Гарчиг» агуулсан б, strong, эсвэл fontWeight≥600 бүхий элемент хайх
    const all = ed.querySelectorAll('*');
    let hasText = false, hasBold = false;
    for (const el of all) {
      if (!el.textContent.includes('Гарчиг')) continue;
      hasText = true;
      const tag = el.tagName;
      const fw  = window.getComputedStyle(el).fontWeight;
      if (tag==='B'||tag==='STRONG'||parseInt(fw)>=600) { hasBold=true; break; }
      if (el.closest('b,strong')) { hasBold=true; break; }
    }
    return [hasText, hasBold];
  }
},

/* 2 */ {
  title: "Бүх текстийг дунд зэрэгцүүлэх",
  desc:  "Бүх текстийг дунд (Center) зэрэгцүүл.",
  reqs:  ["Бүх текстийг", "Center зэрэгцүүлэлттэй байх"],
  hint:  "1. текстний урд курсор тавь.\n2. Home → Paragraph → «≡≡» товч дар  (эсвэл Ctrl+E).",
  check(ed) {
    const p = firstPara(ed);
    if (!p) return [false, false];
    const align = p.style.textAlign || window.getComputedStyle(p).textAlign;
    return [true, align === 'center'];
  }
},

/* 3 */ {
  title: "Font хэмжээг 18+ болгох",
  desc:  "Бүх текстийн font хэмжээг 18 буюу түүнээс дээш болго.",
  reqs:  ["Бүх текстийн", "Font хэмжээ ≥18px байх"],
  hint:  "1. Бүх текстийг сонго.\n2. Home → Font → размерын dropdown-с 18, 24 эсвэл 36 сонго.\n   (Эсвэл «A▲» товч дар)",
  check(ed) {
    const p = firstPara(ed);
    if (!p) return [false, false];
    for (const el of [p, ...p.querySelectorAll('*')]) {
      if (parseFloat(window.getComputedStyle(el).fontSize) >= 18) return [true, true];
    }
    return [true, false];
  }
},

/* 4 */ {
  title: "Бүх текстийг Italic болгох",
  desc:  "Бүх текстийг налуу (Italic) болго.",
  reqs:  ["Бүх текстийг", "Italic форматтай байх"],
  hint:  "1. Бүх текстийг сонго (Монгол хэлний...).\n2. Home → Font → «I» товч дар  (эсвэл Ctrl+I).",
  check(ed) {
    const ps = contentParas(ed);
    if (ps.length < 2) return [false, false];
    const p  = ps[1];
    const fs = window.getComputedStyle(p).fontStyle;
    const ok = fs==='italic'||fs==='oblique'||!!p.querySelector('i,em')||!!p.closest('i,em');
    return [true, ok];
  }
},

/* 5 */ {
  title: "Бүх текстийг Underline болгох",
  desc:  "Бүх текстийн текстэд доогуур зураас (Underline) хэрэглэ.",
  reqs:  ["Бүх текст байх", "Underline форматтай байх"],
  hint:  "1. Бүх текстийг сонго (Word программ...).\n2. Home → Font → «U» товч дар  (эсвэл Ctrl+U).",
  check(ed) {
    const ps = contentParas(ed);
    if (ps.length < 3) return [false, false];
    const p  = ps[2];
    const td = window.getComputedStyle(p).textDecoration;
    const ok = td.includes('underline')||!!p.querySelector('u')||!!p.closest('u');
    return [true, ok];
  }
},

/* 6 */ {
  title: "Цэгтэй жагсаалт оруулах",
  desc:  "Документэд цэгтэй (bullet) жагсаалт оруул.\nДор хаяж 2 зүйл байх ёстой.",
  reqs:  ["Цэгтэй жагсаалт (ul) байх", "Дор хаяж 2 зүйл байх"],
  hint:  "1. Шинэ мөрт курсор тавь.\n2. Home → Paragraph → «☰•» товч дар.\n3. Текст бич, Enter дарж 2-р зүйл нэм.",
  check(ed) {
    const ul = ed.querySelector('ul');
    if (!ul) return [false, false];
    return [true, ul.querySelectorAll('li').length >= 2];
  }
},

/* 7 */ {
  title: "Дугаарлагдсан жагсаалт оруулах",
  desc:  "Документэд дугаарлагдсан жагсаалт оруул.\nДор хаяж 3 зүйл байх ёстой.",
  reqs:  ["Дугаарлагдсан жагсаалт (ol) байх", "Дор хаяж 3 зүйл байх"],
  hint:  "1. Шинэ мөрт курсор тавь.\n2. Home → Paragraph → «1≡» товч дар.\n3. Текст бичиж 3 зүйл нэм.",
  check(ed) {
    const ol = ed.querySelector('ol');
    if (!ol) return [false, false];
    return [true, ol.querySelectorAll('li').length >= 3];
  }
},

/* 8 */ {
  title: "Хүснэгт оруулах",
  desc:  "Insert → Table ашиглан дор хаяж\n2 мөр, 2 баганатай хүснэгт оруул.",
  reqs:  ["Хүснэгт (table) байх", "Дор хаяж 2 мөр байх", "Дор хаяж 2 багана байх"],
  hint:  "1. Insert tab дээр дар.\n2. «⊞ Table» товч дар.\n3. Мөр: 2, Багана: 2 гэж оруулаад OK дар.",
  check(ed) {
    const t = ed.querySelector('table');
    if (!t) return [false, false, false];
    const rows = t.querySelectorAll('tr');
    const cols = rows[0] ? rows[0].querySelectorAll('td,th').length : 0;
    return [true, rows.length >= 2, cols >= 2];
  }
},

/* 9 */ {
  title: "Зураг оруулах",
  desc:  "Insert → Pictures дарж URL оруулан зураг нэм.",
  reqs:  ["Зураг (img) байх"],
  hint:  "1. Insert tab дээр дар.\n2. «🖼️ Pictures» товч дар.\n3. URL оруул.  Жишээ: https://picsum.photos/300/150",
  check(ed) {
    return [!!ed.querySelector('img')];
  }
},

/* 10 */ {
  title: "Холбоос оруулах",
  desc:  "Insert → Link ашиглан дор хаяж нэг холбоос оруул.",
  reqs:  ["Холбоос (a) байх", "Холбоос href хаягтай байх"],
  hint:  "1. Insert tab дээр дар.\n2. «🔗 Link» товч дар.\n3. URL: https://www.google.mn гэж оруулаад «Оруулах» дар.",
  check(ed) {
    const a = ed.querySelector('a[href]');
    if (!a) return [false, false];
    return [true, (a.getAttribute('href')||'').trim().length > 0];
  }
},

/* 11 */ {
  title: "Heading 1 стиль хэрэглэх",
  desc:  "Ямар нэг текстэд «Heading 1» стиль хэрэглэ.",
  reqs:  ["H1 гарчиг байх"],
  hint:  "1. Параграфт курсор тавь.\n2. Home → Styles → «Heading 1» товч дар.",
  check(ed) {
    return [!!ed.querySelector('h1')];
  }
},

/* 12 */ {
  title: "Үсгийн өнгийг өөрчлөх",
  desc:  "Ямар нэг текстийн үсгийн өнгийг\nцагаан эсвэл хар биш өнгө болго.",
  reqs:  ["Хар/цагаан бус өнгөтэй текст байх"],
  hint:  "1. Текстийг сонго.\n2. Home → Font → «A» (Font Color) товчны дэргэдх өнгийг дар.\n3. Улаан, хөх, ногоон гэх мэт өнгө сонго.",
  check(ed) {
    const SKIP = new Set(['black','#000','#000000','rgb(0, 0, 0)','rgb(0,0,0)',
                          'white','#fff','#ffffff','rgb(255, 255, 255)','rgb(255,255,255)','']);
    for (const el of ed.querySelectorAll('[style*="color"]')) {
      const c = el.style.color;
      if (c && !SKIP.has(c)) return [true];
    }
    for (const el of ed.querySelectorAll('font[color]')) {
      const c = (el.getAttribute('color')||'').toLowerCase();
      if (c && !SKIP.has(c)) return [true];
    }
    return [false];
  }
},

/* 13 */ {
  title: "Текстийг Justify болгох",
  desc:  "Дор хаяж нэг параграфыг хоёр талаасаа жигд (Justify) болго.",
  reqs:  ["Justify зэрэгцүүлэлттэй параграф байх"],
  hint:  "1. Параграфт курсор тавь.\n2. Home → Paragraph → «⬅≡⬅» товч дар  (эсвэл Ctrl+J).",
  check(ed) {
    for (const el of ed.querySelectorAll('p,h1,h2,h3,li,div')) {
      const a = el.style.textAlign || window.getComputedStyle(el).textAlign;
      if (a === 'justify') return [true];
    }
    return [false];
  }
},

/* 14 */ {
  title: "Header болон Footer өөрчлөх",
  desc:  "Header болон Footer хэсгийн\nанхны текстийг өөр зүйл болго.",
  reqs:  ["Header өөрчлөгдсөн байх", "Footer өөрчлөгдсөн байх"],
  hint:  "1. Insert → «⬆️ Header» товч → текст оруул.\n2. Insert → «⬇️ Footer» товч → текст оруул.\n   (Эсвэл header/footer дотор шууд дарж засна.)",
  check(ed) {
    const h = ed.querySelector('.header-zone, #hdrZone');
    const f = ed.querySelector('.footer-zone, #ftrZone');
    const hOk = h && h.textContent.trim() !== 'Header area' && h.textContent.trim() !== '';
    const fOk = f && f.textContent.trim() !== 'Footer area' && f.textContent.trim() !== '';
    return [!!hOk, !!fOk];
  }
},

/* 15 */ {
  title: "Текстийг Highlight болгох",
  desc:  "Ямар нэг текстийг шар буюу өөр өнгөөр тодруул (Highlight).",
  reqs:  ["Тодруулагдсан текст байх"],
  hint:  "1. Текстийг сонго.\n2. Home → Font → «ab» (Highlight) товч дар.\n3. Шар эсвэл өөр өнгө сонго.",
  check(ed) {
    const SKIP = new Set(['transparent','rgba(0, 0, 0, 0)','rgba(0,0,0,0)','',
                          'white','#fff','#ffffff','rgb(255, 255, 255)','rgb(255,255,255)']);
    for (const el of ed.querySelectorAll('[style]')) {
      const bg = el.style.backgroundColor || el.style.background;
      if (bg && !SKIP.has(bg) && !bg.includes('gradient')) return [true];
    }
    return [false];
  }
},

/* 16 */ {
  title: "WordArt оруулах",
  desc:  "Insert → «✨ WordArt» товч ашиглан\nтом өнгөт WordArt текст оруул.",
  reqs:  ["WordArt (gradient текст) байх"],
  hint:  "1. Insert tab дээр дар.\n2. «✨ WordArt» товч дар.\n3. Текст оруулах цонхонд «Миний нэр» гэж бичиж OK дар.",
  check(ed) {
    for (const el of ed.querySelectorAll('div[style],span[style]')) {
      const s = el.style;
      if (s.webkitTextFillColor==='transparent' &&
          (s.background||s.backgroundImage||'').includes('gradient')) return [true];
    }
    return [false];
  }
},

/* 17 */ {
  title: "Page Break оруулах",
  desc:  "Insert → «⬛ Page Break» товч ашиглан\nхуудас таслалт оруул.",
  reqs:  ["Page break элемент байх"],
  hint:  "1. Insert tab дээр дар.\n2. «⬛ Page Break» товч дар.\n   Тасалсан шугам харагдах ёстой.",
  check(ed) {
    return [!!ed.querySelector('[style*="page-break"]')];
  }
},

/* 18 */ {
  title: "Comment (тайлбар) нэмэх",
  desc:  "Review → Comment эсвэл Insert → Comment\nтовч ашиглан тайлбар нэм.",
  reqs:  ["Comment элемент байх"],
  hint:  "1. Review tab дээр дар.\n2. «💬 Comment» товч дар.\n3. Тайлбар текст бич, OK дар.\n   Шар хайрцагт тайлбар харагдана.",
  check(ed) {
    return [ed.querySelectorAll('[style*="fff0b8"]').length > 0];
  }
},

/* 19 */ {
  title: "Bold + Italic + Underline нэгэн зэрэг",
  desc:  "Ямар нэг текстэд Bold, Italic, Underline\nгурвыг нэгэн зэрэг хэрэглэ.",
  reqs:  ["Bold байх", "Italic байх", "Underline байх"],
  hint:  "1. Ямар нэг текстийг сонго.\n2. Ctrl+B (Bold) дар.\n3. Ctrl+I (Italic) дар.\n4. Ctrl+U (Underline) дар.\n   Гурав нь зэрэг хэрэгладсан байх ёстой.",
  check(ed) {
    // Computed style-р шалгах
    for (const el of ed.querySelectorAll('*')) {
      if (el.children.length > 6) continue;
      const s  = window.getComputedStyle(el);
      const fw = parseInt(s.fontWeight);
      const bold   = fw >= 600 || s.fontWeight === 'bold';
      const italic = s.fontStyle === 'italic' || s.fontStyle === 'oblique';
      const uline  = s.textDecoration.includes('underline');
      if (bold && italic && uline) return [true, true, true];
    }
    // Tag нэрээр шалгах: b нь i, u агуулах эсвэл эцэг нь тийм байх
    for (const b of ed.querySelectorAll('b,strong')) {
      const hasI = !!b.querySelector('i,em') || !!b.closest('i,em');
      const hasU = !!b.querySelector('u')    || !!b.closest('u') ||
                   window.getComputedStyle(b).textDecoration.includes('underline');
      if (hasI && hasU) return [true, true, true];
    }
    return [false, false, false];
  }
},

/* 20 */ {
  title: "Мэргэжлийн баримт бичиг бүтээх",
  desc:  "Бүх мэдлэгийг нэгтгэн мэргэжлийн баримт үүсгэ:\n• H1 гарчиг байх\n• Bold параграф байх\n• Жагсаалт байх\n• Хүснэгт байх",
  reqs:  ["H1 гарчиг байх", "Bold параграф байх", "Жагсаалт (ul/ol) байх", "Хүснэгт (table) байх"],
  hint:  "Энэ бол нэгтгэх сүүлийн даалгавар!\n1. Home → Styles → «Heading 1» дарж H1 нэм.\n2. Параграфыг Bold болго (Ctrl+B).\n3. «☰•» дарж жагсаалт нэм.\n4. Insert → «⊞ Table» дарж хүснэгт нэм.",
  check(ed) {
    const hasH1    = !!ed.querySelector('h1');
    const hasBold  = !!ed.querySelector('b,strong') ||
                     [...ed.querySelectorAll('p,li,h2,h3')].some(el => parseInt(window.getComputedStyle(el).fontWeight) >= 700);
    const hasList  = !!(ed.querySelector('ul') || ed.querySelector('ol'));
    const hasTable = !!ed.querySelector('table');
    return [hasH1, hasBold, hasList, hasTable];
  }
}

]; // END QUESTS

// ════════════════════════════════════════
// STATE
// ════════════════════════════════════════
const editor   = document.getElementById('editor');
const statusBox = document.getElementById('statusBox');

let histStack = [], redoStack = [], clip = '';
let xp = 0, streak = 0, curQ = 0, done = [];
let hintOpen = false, curZoom = 1;
let watermarkOn = false, pageBorderOn = false;

// ════════════════════════════════════════
// HISTORY
// ════════════════════════════════════════
function saveHist() { histStack.push(editor.innerHTML); if(histStack.length>150)histStack.shift(); redoStack=[]; }
saveHist();
function undoAction() { if(histStack.length<=1)return; redoStack.push(histStack.pop()); editor.innerHTML=histStack[histStack.length-1]; updateStats(); setStatus('Буцаасан'); }
function redoAction() { if(!redoStack.length)return; const s=redoStack.pop(); histStack.push(s); editor.innerHTML=s; updateStats(); setStatus('Дахин хийсэн'); }

// ════════════════════════════════════════
// QUEST
// ════════════════════════════════════════
function loadQuest(idx) {
  if (idx >= QUESTS.length) { showAllDone(); return; }
  curQ = idx; hintOpen = false;
  const q = QUESTS[idx];
  document.getElementById('qNum').textContent   = idx+1;
  document.getElementById('qTitle').textContent = q.title;
  document.getElementById('qDesc').textContent  = q.desc;
  document.getElementById('hintBody').textContent = q.hint;
  document.getElementById('hintBody').classList.remove('open');
  document.getElementById('hintBtn').textContent = '💡 Hint харах';

  const ul = document.getElementById('qReqs');
  ul.innerHTML = '';
  q.reqs.forEach((r,i) => {
    const li = document.createElement('li');
    li.className = 'req-item';
    li.innerHTML = `<div class="req-dot" id="rd${i}"></div><span>${r}</span>`;
    ul.appendChild(li);
  });

  statusBox.textContent = 'Даалгавраа хийж "Шалгах" товчийг дарна уу.';
  statusBox.style.background  = 'var(--warn)';
  statusBox.style.borderColor = 'var(--warn-border)';
  updateProgress();
}

function toggleHint() {
  hintOpen = !hintOpen;
  document.getElementById('hintBody').classList.toggle('open', hintOpen);
  document.getElementById('hintBtn').textContent = hintOpen ? '💡 Hint хаах' : '💡 Hint харах';
}

function checkTask() {
  const q = QUESTS[curQ];
  let res;
  try { res = q.check(editor); }
  catch(e) { res = q.reqs.map(()=>false); }

  // Dot update
  res.forEach((ok, i) => {
    const d = document.getElementById(`rd${i}`);
    if (!d) return;
    d.classList.toggle('ok',   ok===true);
    d.classList.toggle('fail', ok===false);
  });

  const allOk = res.every(r => r===true);

  if (allOk) {
    if (!done.includes(curQ)) { done.push(curQ); streak++; addXP(10+(streak>=3?5:0)); }
    statusBox.textContent  = '✅ Зөв! Дараагийн даалгавар руу шилжиж байна...';
    statusBox.style.background  = 'var(--success)';
    statusBox.style.borderColor = 'var(--success-border)';
    showFlash(q.title);
    setTimeout(() => loadQuest(curQ+1), 1800);
  } else {
    streak = 0;
    const n = res.filter(r=>!r).length;
    statusBox.textContent  = `❌ Буруу — ${n} шаардлага биелсэнгүй. Hint харж дахин оролдоорой.`;
    statusBox.style.background  = 'var(--danger)';
    statusBox.style.borderColor = 'var(--danger-border)';
    updateStreakUI();
  }
}

function showFlash(title) {
  const emj = ['🎉','🚀','⭐','🏆','✨','💫','🎯','🔥'];
  document.getElementById('sfEmoji').textContent = emj[Math.floor(Math.random()*emj.length)];
  document.getElementById('sfTitle').textContent = '✅ '+title;
  const el = document.getElementById('successFlash');
  el.classList.add('show');
  setTimeout(()=>el.classList.remove('show'), 1600);
}
function showAllDone() {
  document.getElementById('finalXp').textContent = xp;
  document.getElementById('allDoneDlg').classList.add('show');
}
function restartGame() {
  document.getElementById('allDoneDlg').classList.remove('show');
  done=[]; xp=0; streak=0; curQ=0;
  updateXpUI(); updateStreakUI();
  editor.innerHTML = `
    <div class="header-zone" id="hdrZone">Header area</div>
    <p id="ln1"><b>Гарчиг</b></p>
    <p id="ln2">Монгол хэлний анхдугаар хичээл.</p>
    <p id="ln3">Word программ нь олон боломжтой.</p>
    <p id="ln4">Текстийг засаж форматлаж болно.</p>
    <div class="footer-zone" id="ftrZone">Footer area</div>`;
  saveHist(); updateStats(); loadQuest(0);
}
function addXP(n) { xp+=n; updateXpUI(); setStatus(`+${n} XP авлаа 🎉`); }
function updateXpUI()    { ['xpLbl','sbXp'].forEach(id=>document.getElementById(id).textContent=xp); document.getElementById('lvlLbl').textContent=Math.floor(xp/50)+1; }
function updateStreakUI(){ document.getElementById('strkLbl').textContent=streak; }
function updateProgress(){ const d=done.length; document.getElementById('progFill').style.width=(d/20*100)+'%'; document.getElementById('progText').textContent=`${d} / 20`; }

// ════════════════════════════════════════
// HELPERS
// ════════════════════════════════════════
function contentParas(ed) {
  return [...ed.querySelectorAll('p,h1,h2,h3')].filter(el=>
    !el.classList.contains('header-zone')&&!el.classList.contains('footer-zone')&&
    el.id!=='hdrZone'&&el.id!=='ftrZone'&&el.textContent.trim().length>0);
}
function firstPara(ed){ return contentParas(ed)[0]||null; }

// ════════════════════════════════════════
// EDITOR COMMANDS
// ════════════════════════════════════════
function fe(){ editor.focus(); }
function execCmd(cmd,val=null){ saveHist(); fe(); document.execCommand(cmd,false,val); updateStats(); }
function applyFontSize(v){ execCmd('fontSize',v); }
function growFont()   { execCmd('fontSize','5'); }
function shrinkFont() { execCmd('fontSize','2'); }
function applyTextColor(c){ document.getElementById('tcBar').style.background=c; execCmd('foreColor',c); }
function applyHighlight(c){ document.getElementById('hlBar').style.background=c; execCmd('hiliteColor',c); }
function clearFormatting(){ execCmd('removeFormat'); }
function applyStyle(s){ saveHist(); fe(); const m={normal:'p',heading1:'h1',heading2:'h2',title:'h1',subtitle:'h3',quote:'blockquote'}; document.execCommand('formatBlock',false,m[s]||'p'); updateStats(); }
function selectAllText(){ fe(); document.execCommand('selectAll'); }

// ════════════════════════════════════════
// CLIPBOARD
// ════════════════════════════════════════
function copyText(){ const t=window.getSelection().toString(); if(!t)return; clip=t; navigator.clipboard.writeText(t).catch(()=>{}); setStatus('Хуулагдсан'); }
function cutText(){  const t=window.getSelection().toString(); if(!t)return; clip=t; navigator.clipboard.writeText(t).catch(()=>{}); execCmd('delete'); setStatus('Хайчлагдсан'); }
function pasteText(){ fe(); navigator.clipboard.readText().then(t=>{ saveHist(); document.execCommand('insertText',false,t||clip); updateStats(); setStatus('Буулгасан'); }).catch(()=>{ if(clip){saveHist();document.execCommand('insertText',false,clip);updateStats();} }); }

// ════════════════════════════════════════
// INSERT
// ════════════════════════════════════════
function iHTML(html){ saveHist(); fe(); document.execCommand('insertHTML',false,html); updateStats(); }
function insertDate()      { execCmd('insertText', new Date().toLocaleDateString('mn-MN')); }
function insertPageBreak() { iHTML(`<div style="page-break-after:always;border-top:2px dashed #c7cfdb;margin:22px 0;text-align:center;font-size:11px;color:#aaa;">— Хуудас таслалт —</div><p></p>`); }
function insertCoverPage() { saveHist(); editor.innerHTML=`<div class="header-zone" id="hdrZone">Header area</div><div style="text-align:center;padding:100px 20px;"><h1 style="font-size:40px;">Баримт бичгийн гарчиг</h1><p style="font-size:18px;color:#666;margin-top:12px;">Дэд гарчиг</p><p style="font-size:14px;margin-top:40px;"><?= htmlspecialchars($_SESSION['username']) ?> — ${new Date().getFullYear()}</p></div><div class="footer-zone" id="ftrZone">Footer area</div>`; updateStats(); }
function insertTable(){
  const r=parseInt(prompt('Мөрийн тоо:','2'))||2;
  const c=parseInt(prompt('Баганын тоо:','2'))||2;
  let h='<table class="simple-table">';
  for(let i=0;i<r;i++){h+='<tr>';for(let j=0;j<c;j++)h+=`<td>Нүд ${i+1}.${j+1}</td>`;h+='</tr>';}
  iHTML(h+'</table><p></p>');
}
function insertImageByUrl(){ const u=prompt('Зургийн URL:','https://picsum.photos/400/200'); if(u)iHTML(`<img src="${esc(u)}" alt="зураг" style="max-width:100%;display:block;margin:12px 0;border-radius:4px;">`); }
function insertShape()     { iHTML('<div style="width:100px;height:100px;background:#cce4f7;border:2px solid #2b579a;border-radius:8px;display:inline-block;margin:8px 0;"></div><p></p>'); }
function insertWordArt(){
  const t=prompt('WordArt текст:','Миний WordArt');
  if(t) iHTML(`<div style="font-size:36px;font-weight:900;background:linear-gradient(135deg,#2b579a,#00b4d8);-webkit-background-clip:text;-webkit-text-fill-color:transparent;margin:14px 0;">${esc(t)}</div><p></p>`);
}
function insertTextBox()   { iHTML('<div style="border:2px solid #2b579a;padding:12px;margin:10px 0;border-radius:6px;min-height:50px;background:#f7f9fd;">Текст хайрцаг</div><p></p>'); }
function insertEquation()  { const e=prompt('Томьёо:','E = mc²'); if(e)iHTML(`<span style="font-family:Courier New;background:#f5f5f5;padding:2px 8px;border-radius:4px;border:1px solid #ddd;">${esc(e)}</span>`); }
function insertSymbol()    { const s=prompt('Тэмдэгт:','©'); if(s)execCmd('insertText',s); }
function insertComment()   { const n=prompt('Тайлбар бич:','Энд тайлбар'); if(!n)return; iHTML(`<span style="background:#fff0b8;border:1px solid #e5cf77;border-radius:4px;padding:2px 8px;font-size:12px;">[💬 ${esc(n)}]</span>`); }
function insertBookmark()  { const n=prompt('Bookmark нэр:','Хэсэг1'); if(!n)return; iHTML(`<span id="${esc(n)}" style="background:#eef4ff;border:1px solid #c9d8f8;padding:1px 6px;border-radius:4px;font-size:12px;">🔖${esc(n)}</span>`); }
function insertPageNumber(){ const f=editor.querySelector('.footer-zone,#ftrZone'); if(f)f.innerHTML='Хуудас 1'; }
function insertHeader(){ const h=editor.querySelector('.header-zone,#hdrZone'); if(!h)return; const v=prompt('Header текст:',h.textContent); if(v!==null)h.textContent=v; }
function insertFooter(){ const f=editor.querySelector('.footer-zone,#ftrZone'); if(!f)return; const v=prompt('Footer текст:',f.textContent); if(v!==null)f.textContent=v; }

// LINK
function openLinkDialog(){ document.getElementById('linkDlg').classList.add('show'); }
function closeDlg(id){ document.getElementById(id).classList.remove('show'); }
function doInsertLink(){
  const url=document.getElementById('lUrl').value.trim();
  const txt=document.getElementById('lText').value.trim();
  if(!url)return;
  saveHist(); fe();
  if(window.getSelection().toString()) document.execCommand('createLink',false,url);
  else document.execCommand('insertHTML',false,`<a href="${esc(url)}" target="_blank">${esc(txt||url)}</a>`);
  closeDlg('linkDlg'); document.getElementById('lUrl').value=''; document.getElementById('lText').value=''; updateStats();
}

// FIND
function openFindDialog(){ document.getElementById('findDlg').classList.add('show'); }
function doFind()   { const q=document.getElementById('fFind').value; if(!q)return; alert(editor.innerText.toLowerCase().includes(q.toLowerCase()) ? `"${q}" олдлоо.` : `"${q}" олдсонгүй.`); }
function doReplace(){ const f=document.getElementById('fFind').value; const r=document.getElementById('fRepl').value; if(!f)return; saveHist(); editor.innerHTML=editor.innerHTML.replace(new RegExp(escRe(f),'gi'),r); closeDlg('findDlg'); updateStats(); }

// DESIGN
function setTheme(bg,color){ editor.style.background=bg; editor.style.color=color; }
function toggleWatermark(){ watermarkOn=!watermarkOn; editor.style.backgroundImage=watermarkOn?'repeating-linear-gradient(45deg,rgba(0,0,0,.04) 0,rgba(0,0,0,.04) 1px,transparent 0,transparent 50%)':''; editor.style.backgroundSize=watermarkOn?'30px 30px':''; }
function showPageColor(){ const c=prompt('Хуудасны өнгө (hex):','#ffffff'); if(c)editor.style.background=c; }
function togglePageBorder(){ pageBorderOn=!pageBorderOn; editor.style.boxShadow=pageBorderOn?'0 2px 12px rgba(0,0,0,0.15),inset 0 0 0 5px #8aa7d4':'0 2px 12px rgba(0,0,0,0.15)'; }

// LAYOUT
function setMargins(t){ const m={normal:'90px 80px',narrow:'60px 45px',wide:'110px 110px'}; if(m[t])editor.style.padding=m[t]; }
function setOrientation(t){ if(t==='portrait'){editor.style.width='850px';editor.style.minHeight='1100px';}else{editor.style.width='1100px';editor.style.minHeight='750px';} }

// REVIEW
function spellCheck()   { alert('Алдаа олдсонгүй (демо).'); }
function showWordCount() { const s=stats(); alert(`Үг: ${s.w}\nТэмдэгт: ${s.c}`); }
function readAloud()     { const t=window.getSelection().toString().trim()||editor.innerText.trim().slice(0,400); if(!t)return; if('speechSynthesis'in window){const u=new SpeechSynthesisUtterance(t);speechSynthesis.cancel();speechSynthesis.speak(u);}else alert('Дэмжигдэхгүй.'); }

// FILE
function showFilePanel(){ document.getElementById('fileOverlay').classList.remove('hidden'); }
function closeFilePanel(){ document.getElementById('fileOverlay').classList.add('hidden'); activateTab('home'); }
function newDocument(){ if(!confirm('Шинэ баримт нээх үү? Одоогийн агуулга устана.'))return; editor.innerHTML=`<div class="header-zone" id="hdrZone">Header area</div><p id="ln1"><b>Гарчиг</b></p><p id="ln2">Монгол хэлний анхдугаар хичээл.</p><p id="ln3">Word программ нь олон боломжтой.</p><p id="ln4">Текстийг засаж форматлаж болно.</p><div class="footer-zone" id="ftrZone">Footer area</div>`; saveHist(); updateStats(); setStatus('Шинэ баримт'); }
function saveDocument()  { const h=`<!DOCTYPE html><html lang="mn"><head><meta charset="UTF-8"><title>Document</title></head><body>${editor.innerHTML}</body></html>`; const a=document.createElement('a'); a.href=URL.createObjectURL(new Blob([h],{type:'text/html;charset=utf-8'})); a.download='Document.html'; a.click(); URL.revokeObjectURL(a.href); setStatus('Хадгалагдсан'); }
function printDocument() { const w=window.open('','_blank'); w.document.write(`<html><head><title>Хэвлэх</title><style>body{font-family:Arial,sans-serif;padding:40px;line-height:1.6;}table{border-collapse:collapse;width:100%;}td,th{border:1px solid #777;padding:8px;}img{max-width:100%;}</style></head><body>${editor.innerHTML}</body></html>`); w.document.close(); w.focus(); w.print(); }

// VIEW
function setZoom(s){ curZoom=s; editor.style.transform=`scale(${s})`; const p=Math.round(s*100)+'%'; ['zLbl','sbZoom'].forEach(id=>document.getElementById(id).textContent=p); setStatus('Zoom: '+p); }
function toggleRuler(){ document.getElementById('rulerWrap').classList.toggle('hidden'); }
function toggleGrid() { editor.classList.toggle('grid-on'); }
function toggleDarkMode(){ document.body.classList.toggle('dark'); }

// STATS
function stats(){ const t=editor.innerText.replace(/\s+/g,' ').trim(); return {w:t?t.split(' ').filter(Boolean).length:0,c:editor.innerText.trim().length}; }
function updateStats(){ const s=stats(); ['wcLive'].forEach(id=>document.getElementById(id).textContent=s.w); document.getElementById('ccLive').textContent=s.c; document.getElementById('sbWords').textContent=s.w+' үг'; }
function setStatus(msg){ document.getElementById('sbLeft').textContent=msg; }

// UTILS
function esc(s){ return String(s).replaceAll('&','&amp;').replaceAll('<','&lt;').replaceAll('>','&gt;').replaceAll('"','&quot;'); }
function escRe(s){ return String(s).replace(/[.*+?^${}()|[\]\\]/g,'\\$&'); }

// TABS
function activateTab(name){
  document.querySelectorAll('.tab').forEach(t=>t.classList.remove('active'));
  document.querySelectorAll('.ribbon-panel').forEach(p=>p.classList.remove('active'));
  const tab=document.querySelector(`[data-tab="${name}"]`); if(tab)tab.classList.add('active');
  const pan=document.getElementById('panel-'+name); if(pan)pan.classList.add('active');
}
document.getElementById('tabsBar').addEventListener('click', e=>{
  const t=e.target.closest('.tab'); if(!t)return;
  const n=t.dataset.tab; if(n==='file'){showFilePanel();return;} activateTab(n);
});

// KEYBOARD
document.addEventListener('keydown', e=>{
  if(!e.ctrlKey)return;
  const k=e.key.toLowerCase();
  if(k==='s'){e.preventDefault();saveDocument();}
  if(k==='p'){e.preventDefault();printDocument();}
  if(k==='f'||k==='h'){e.preventDefault();openFindDialog();}
  if(k==='z'){e.preventDefault();undoAction();}
  if(k==='y'){e.preventDefault();redoAction();}
});

// EDITOR EVENTS
editor.addEventListener('input',()=>{saveHist();updateStats();});
editor.addEventListener('keyup',updateStats);
editor.addEventListener('mouseup',updateStats);

// INIT
activateTab('home');
document.getElementById('chkRuler').checked=true;
updateStats();
loadQuest(0);
</script>
</body>
</html>