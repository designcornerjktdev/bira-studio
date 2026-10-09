import {
  WebGLRenderer,
  Scene,
  PerspectiveCamera,
  Group,
  PlaneGeometry,
  MeshBasicMaterial,
  Mesh,
  CanvasTexture,
  SRGBColorSpace,
  MathUtils,
} from 'three';
import { gsap } from 'gsap';

const CARD_W = 1.6;
const CARD_H = 2.0;
const GROUP_SIZE = 3.6; // perkiraan lebar/tinggi seluruh tumpukan

// Posisi dasar tiap kartu: z lebih besar = lebih dekat ke kamera
const LAYOUT = [
  { x: -0.9, y: 0.55, z: -0.8, rz: 0.1 },
  { x: 0.95, y: 0.9, z: -0.2, rz: -0.08 },
  { x: -0.55, y: -0.85, z: 0.3, rz: -0.06 },
  { x: 1.0, y: -0.6, z: 0.9, rz: 0.09 },
];

/* ---------- Helper gambar kartu (canvas 2D) ---------- */

function roundRect(g, x, y, w, h, r) {
  g.beginPath();
  g.moveTo(x + r, y);
  g.arcTo(x + w, y, x + w, y + h, r);
  g.arcTo(x + w, y + h, x, y + h, r);
  g.arcTo(x, y + h, x, y, r);
  g.arcTo(x, y, x + w, y, r);
  g.closePath();
}

function drawCover(g, img, x, y, w, h) {
  const iw = img.naturalWidth;
  const ih = img.naturalHeight;
  const s = Math.max(w / iw, h / ih);
  const sw = w / s;
  const sh = h / s;
  g.drawImage(img, (iw - sw) / 2, (ih - sh) / 2, sw, sh, x, y, w, h);
}

function wrapLines(g, text, maxW, maxLines) {
  const words = text.split(/\s+/).filter(Boolean);
  const lines = [];
  let line = '';
  for (let i = 0; i < words.length; i++) {
    const test = line ? `${line} ${words[i]}` : words[i];
    if (g.measureText(test).width <= maxW) {
      line = test;
      continue;
    }
    if (line) lines.push(line);
    line = words[i];
    if (lines.length === maxLines) break;
  }
  if (lines.length < maxLines && line) lines.push(line);

  const used = lines.join(' ').split(/\s+/).length;
  if (used < words.length && lines.length) {
    let last = lines[lines.length - 1];
    while (last && g.measureText(`${last}…`).width > maxW) last = last.slice(0, -1);
    lines[lines.length - 1] = `${last.trimEnd()}…`;
  }
  return lines.slice(0, maxLines);
}

function drawHeart(g, x, y, s, color) {
  const k = s / 2;
  g.save();
  g.translate(x, y);
  g.beginPath();
  g.moveTo(0, k * 0.9);
  g.bezierCurveTo(-k * 1.6, -k * 0.1, -k * 0.8, -k * 1.1, 0, -k * 0.35);
  g.bezierCurveTo(k * 0.8, -k * 1.1, k * 1.6, -k * 0.1, 0, k * 0.9);
  g.closePath();
  g.fillStyle = color;
  g.fill();
  g.restore();
}

function drawBubble(g, x, y, s, color) {
  g.save();
  g.strokeStyle = color;
  g.lineWidth = s * 0.11;
  g.lineJoin = 'round';
  g.lineCap = 'round';
  g.beginPath();
  g.arc(x, y, s * 0.45, 0, Math.PI * 2);
  g.stroke();
  g.beginPath();
  g.moveTo(x - s * 0.38, y + s * 0.2);
  g.lineTo(x - s * 0.5, y + s * 0.6);
  g.lineTo(x - s * 0.1, y + s * 0.42);
  g.stroke();
  g.restore();
}

function drawPlane(g, x, y, s, color) {
  g.save();
  g.strokeStyle = color;
  g.lineWidth = s * 0.11;
  g.lineJoin = 'round';
  g.beginPath();
  g.moveTo(x + s * 0.5, y - s * 0.5);
  g.lineTo(x - s * 0.5, y - s * 0.05);
  g.lineTo(x - s * 0.1, y + s * 0.12);
  g.lineTo(x + s * 0.05, y + s * 0.5);
  g.closePath();
  g.stroke();
  g.restore();
}

function drawCard({ img, handle, caption }, W, H, theme, font) {
  const c = document.createElement('canvas');
  c.width = W;
  c.height = H;
  const g = c.getContext('2d');
  const pad = W * 0.055;
  const R = W * 0.06;

  // Badan kartu
  roundRect(g, 0, 0, W, H, R);
  g.fillStyle = theme.bg;
  g.fill();

  // Header: avatar "b" + handle
  const headH = H * 0.11;
  const ar = headH * 0.3;
  g.beginPath();
  g.arc(pad + ar, headH / 2, ar, 0, Math.PI * 2);
  g.fillStyle = theme.accent;
  g.fill();
  g.fillStyle = theme.bg;
  g.font = `700 ${ar * 1.3}px ${font}`;
  g.textAlign = 'center';
  g.textBaseline = 'middle';
  g.fillText('b', pad + ar, headH / 2 + ar * 0.05);
  if (handle) {
    g.fillStyle = theme.ink;
    g.textAlign = 'left';
    g.font = `600 ${W * 0.045}px ${font}`;
    g.fillText(handle, pad + ar * 2 + W * 0.03, headH / 2);
  }

  // Gambar
  const ix = pad;
  const iy = headH;
  const iw = W - pad * 2;
  const ih = H * 0.6;
  g.save();
  roundRect(g, ix, iy, iw, ih, R * 0.6);
  g.clip();
  drawCover(g, img, ix, iy, iw, ih);
  g.restore();

  // Ikon aksi
  const ay = iy + ih + H * 0.055;
  const is = W * 0.055;
  drawHeart(g, pad + is * 0.6, ay, is, theme.accent);
  drawBubble(g, pad + is * 2.4, ay, is, theme.ink);
  drawPlane(g, pad + is * 4.2, ay, is, theme.ink);

  // Caption (maksimal 2 baris)
  if (caption) {
    const fs = W * 0.042;
    g.fillStyle = theme.ink;
    g.textAlign = 'left';
    g.textBaseline = 'top';
    g.font = `400 ${fs}px ${font}`;
    const lines = wrapLines(g, caption, W - pad * 2, 2);
    lines.forEach((ln, i) => g.fillText(ln, pad, ay + H * 0.05 + i * fs * 1.3));
  }

  return c;
}

/* ---------- Scene ---------- */

/** Mengembalikan fungsi destroy, atau null jika 3D tidak bisa dipakai. */
export async function initHero3D(root) {
  const stage = root.querySelector('[data-hero-stage]');
  const mockup = root.querySelector('[data-hero-mockup]');
  if (!stage || !mockup) return null;

  const nodes = Array.from(mockup.querySelectorAll('[data-mock-card]')).slice(0, LAYOUT.length);
  if (!nodes.length) return null;

  // Tunggu font siap supaya teks di kartu memakai font yang benar.
  await document.fonts.ready;

  const items = (
    await Promise.all(
      nodes.map(async (n) => {
        const img = n.querySelector('img');
        if (!img) return null;
        try {
          await img.decode();
        } catch (e) {
          /* lanjut; dicek lewat naturalWidth */
        }
        if (!img.naturalWidth) return null;
        return { img, handle: n.dataset.handle || '', caption: n.dataset.caption || '' };
      })
    )
  ).filter(Boolean);
  if (!items.length) return null;

  const small = window.innerWidth < 768;

  let renderer;
  try {
    renderer = new WebGLRenderer({
      antialias: !small,
      alpha: true,
      powerPreference: 'low-power',
    });
  } catch (e) {
    return null;
  }
  renderer.setClearColor(0x000000, 0);
  renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, small ? 1.5 : 2));
  renderer.domElement.setAttribute('aria-hidden', 'true');
  stage.appendChild(renderer.domElement);

  const scene = new Scene();
  const camera = new PerspectiveCamera(40, 1, 0.1, 50);
  camera.position.z = 8;

  const group = new Group();
  scene.add(group);

  // ---------- Kartu ----------
  const css = getComputedStyle(stage);
  const theme = {
    bg: css.getPropertyValue('--hero-card-bg').trim() || '#ffffff',
    ink: css.getPropertyValue('--hero-card-ink').trim() || '#111111',
    accent: css.getPropertyValue('--hero-3d-color').trim() || '#1faa59',
  };
  const font = getComputedStyle(document.body).fontFamily || 'sans-serif';
  const TW = small ? 400 : 512;
  const TH = small ? 500 : 640;
  const maxAniso = Math.min(4, renderer.capabilities.getMaxAnisotropy());

  const geometry = new PlaneGeometry(CARD_W, CARD_H);
  const cards = items.map((item, i) => {
    const tex = new CanvasTexture(drawCard(item, TW, TH, theme, font));
    tex.colorSpace = SRGBColorSpace;
    tex.anisotropy = maxAniso;
    const material = new MeshBasicMaterial({
      map: tex,
      transparent: true,
      opacity: 0,
      depthWrite: false,
    });
    const mesh = new Mesh(geometry, material);
    const base = LAYOUT[i];
    mesh.userData.intro = 0;
    mesh.position.set(base.x, base.y, base.z);
    mesh.scale.setScalar(0.6);
    group.add(mesh);
    return { mesh, base, speed: 0.5 + (base.z + 1) * 0.5, tex, material };
  });

  // ---------- Ukuran: tumpukan mengikuti posisi area mockup di DOM ----------
  const layout = () => {
    const w = stage.clientWidth;
    const h = stage.clientHeight;
    if (!w || !h) return;
    renderer.setSize(w, h, false);
    camera.aspect = w / h;
    camera.updateProjectionMatrix();

    const s = stage.getBoundingClientRect();
    const m = mockup.getBoundingClientRect();
    const halfH = Math.tan(MathUtils.degToRad(camera.fov / 2)) * camera.position.z;
    const halfW = halfH * camera.aspect;
    const cx = ((m.left + m.width / 2 - s.left) / s.width) * 2 - 1;
    const cy = -(((m.top + m.height / 2 - s.top) / s.height) * 2 - 1);
    group.position.set(cx * halfW, cy * halfH, 0);

    const worldPerPx = (halfH * 2) / s.height;
    group.scale.setScalar(((Math.min(m.width, m.height) * worldPerPx) / GROUP_SIZE) * 1.1);

    if (!raf) renderer.render(scene, camera);
  };

  // ---------- Input ----------
  const target = { x: 0, y: 0 };
  const cur = { x: 0, y: 0 };
  let scrollP = 0;
  let scrollEase = 0;

  const onMove = (e) => {
    const r = root.getBoundingClientRect();
    target.x = ((e.clientX - r.left) / r.width) * 2 - 1;
    target.y = ((e.clientY - r.top) / r.height) * 2 - 1;
  };
  const onScroll = () => {
    const r = root.getBoundingClientRect();
    scrollP = MathUtils.clamp(-r.top / r.height, 0, 1);
  };
  root.addEventListener('pointermove', onMove);
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();
  scrollEase = scrollP;

  // ---------- Loop (berhenti saat tidak terlihat) ----------
  let raf = 0;
  let last = 0;
  let visible = true;
  let lost = false;

  const frame = (now) => {
    raf = requestAnimationFrame(frame);
    const dt = Math.min((now - last) / 1000, 0.05);
    last = now;
    const t = now / 1000;

    const k = Math.min(dt * 4, 1);
    cur.x += (target.x - cur.x) * k;
    cur.y += (target.y - cur.y) * k;
    scrollEase += (scrollP - scrollEase) * Math.min(dt * 6, 1);

    group.rotation.y = cur.x * 0.35;
    group.rotation.x = -cur.y * 0.2;

    cards.forEach(({ mesh, base, speed }, i) => {
      const s = mesh.userData.intro;
      mesh.position.set(
        base.x + cur.x * (0.1 + (base.z + 1) * 0.12),
        base.y + Math.sin(t * 0.8 + i * 1.7) * 0.06 + scrollEase * speed * 1.6 - (1 - s) * 0.9,
        base.z
      );
      mesh.rotation.z = base.rz + scrollEase * speed * 0.12 * (i % 2 ? 1 : -1);
      mesh.scale.setScalar(0.6 + 0.4 * s);
      mesh.material.opacity = MathUtils.clamp(s, 0, 1);
    });

    renderer.render(scene, camera);
  };
  const start = () => {
    if (raf || !visible || document.hidden || lost) return;
    last = performance.now();
    raf = requestAnimationFrame(frame);
  };
  const stop = () => {
    cancelAnimationFrame(raf);
    raf = 0;
  };

  const io = new IntersectionObserver(([entry]) => {
    visible = entry.isIntersecting;
    visible ? start() : stop();
  });
  io.observe(stage);

  const onVisibility = () => (document.hidden ? stop() : start());
  document.addEventListener('visibilitychange', onVisibility);

  const ro = new ResizeObserver(layout);
  ro.observe(stage);
  ro.observe(mockup);

  // Jika konteks WebGL hilang, kembali ke kartu 2D.
  const onLost = (e) => {
    e.preventDefault();
    lost = true;
    stop();
    stage.classList.remove('is-3d');
    root.classList.remove('is-3d-cards');
  };
  renderer.domElement.addEventListener('webglcontextlost', onLost);

  // ---------- Mulai ----------
  layout();
  renderer.render(scene, camera);
  stage.classList.add('is-3d');
  root.classList.add('is-3d-cards');
  start();

  cards.forEach(({ mesh }, i) => {
    gsap.to(mesh.userData, {
      intro: 1,
      duration: 1.1,
      delay: 0.1 + i * 0.15,
      ease: 'back.out(1.5)',
    });
  });

  // ---------- Pembersihan ----------
  return function destroy() {
    stop();
    cards.forEach(({ mesh }) => gsap.killTweensOf(mesh.userData));
    io.disconnect();
    ro.disconnect();
    root.removeEventListener('pointermove', onMove);
    window.removeEventListener('scroll', onScroll);
    document.removeEventListener('visibilitychange', onVisibility);
    renderer.domElement.removeEventListener('webglcontextlost', onLost);
    stage.classList.remove('is-3d');
    root.classList.remove('is-3d-cards');
    cards.forEach(({ tex, material }) => {
      tex.dispose();
      material.dispose();
    });
    geometry.dispose();
    renderer.dispose();
    renderer.domElement.remove();
  };
}