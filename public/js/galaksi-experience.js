(function () {
  "use strict";

  // =========================================================================
  // 1. MESH DRIFT WEBGL BACKGROUND
  // =========================================================================
  const BG_VERT = `
attribute vec2 a_position;
void main() {
  gl_Position = vec4(a_position, 0.0, 1.0);
}
`;

  const BG_FRAG = `
#ifdef GL_FRAGMENT_PRECISION_HIGH
precision highp float;
#else
precision mediump float;
#endif

uniform vec3 u_colors[8];
uniform vec4 u_scene;
uniform vec4 u_shape;
uniform vec4 u_surface;
uniform vec4 u_finish;
uniform vec4 u_transform;
uniform vec4 u_space;
uniform vec4 u_cursor;

#define u_resolution u_scene.xy
#define u_time u_scene.z
#define u_colorCount u_scene.w
#define u_scale u_shape.x
#define u_intensity u_shape.y
#define u_warp u_shape.w
#define u_detail u_surface.x
#define u_contrast u_surface.y
#define u_brightness u_surface.z
#define u_saturation u_surface.w
#define u_hue u_finish.x
#define u_vignette u_finish.y
#define u_blur u_finish.z
#define u_grain u_finish.w
#ifdef GL_FRAGMENT_PRECISION_HIGH
#define u_seed u_transform.x
#else
#define u_seed mod(u_transform.x, 31.0)
#endif
#define u_rotate u_transform.y
#define u_drift u_transform.z
#define u_oklab u_transform.w
#define u_offset u_space.xy
#define u_mouse u_space.zw
#define u_cursorPresence u_cursor.x
#define u_cursorEffect u_cursor.y
#define u_cursorStrength u_cursor.z
#define u_cursorRadius u_cursor.w

float hash21(vec2 p) {
#ifndef GL_FRAGMENT_PRECISION_HIGH
  p = mod(p, 31.0);
#endif
  p = fract(p * vec2(234.34, 435.345));
  p += dot(p, p + 34.23);
  return fract(p.x * p.y);
}

float grainHash(vec2 p) {
  vec3 p3 = fract(vec3(p.xyx) * 0.1031);
  p3 += dot(p3, p3.yzx + 33.33);
  return fract((p3.x + p3.y) * p3.z);
}

float noise(vec2 p) {
  vec2 i = floor(p);
  vec2 f = fract(p);
  vec2 u = f * f * (3.0 - 2.0 * f);
  return mix(
    mix(hash21(i), hash21(i + vec2(1.0, 0.0)), u.x),
    mix(hash21(i + vec2(0.0, 1.0)), hash21(i + vec2(1.0, 1.0)), u.x),
    u.y);
}

float fbm(vec2 p) {
  float v = 0.0;
  float a = 0.5;
  for (int i = 0; i < 5; i++) {
    v += a * noise(p);
    p = p * 2.03 + vec2(17.0, 9.2);
    a *= 0.5;
  }
  return v;
}

vec3 hueRotate(vec3 col, float a) {
  const mat3 toYIQ = mat3(0.299, 0.596, 0.211,
                          0.587, -0.274, -0.523,
                          0.114, -0.322, 0.312);
  const mat3 toRGB = mat3(1.0, 1.0, 1.0,
                          0.956, -0.272, -1.106,
                          0.621, -0.647, 1.703);
  vec3 yiq = toYIQ * col;
  float ca = cos(a), sa = sin(a);
  yiq = vec3(yiq.x, yiq.y * ca - yiq.z * sa, yiq.y * sa + yiq.z * ca);
  return toRGB * yiq;
}

vec3 shade(vec2 uv, vec2 p, float t) {
  vec3 acc = u_colors[0] * 0.15;
  float total = 0.15;
  for (int i = 0; i < 8; i++) {
    if (float(i) >= u_colorCount) break;
    float fi = float(i);
    vec2 c = vec2(
      sin(t * (0.21 + fi * 0.071) + fi * 2.4 + u_seed),
      cos(t * (0.17 + fi * 0.093) + fi * 1.7)) * (0.45 + u_intensity * 0.35);
    float w = exp(-dot(p - c, p - c) * 6.0);
    acc += u_colors[i] * w;
    total += w;
  }
  return acc / total;
}

void main() {
  vec2 uv = gl_FragCoord.xy / u_resolution.xy;
  vec2 screenUv = uv;
  vec2 p = (gl_FragCoord.xy - 0.5 * u_resolution.xy)
    / min(u_resolution.x, u_resolution.y);
  float cursorMask = 0.0;

  if (u_cursorPresence > 0.001) {
    vec2 cursor = (0.5 * u_mouse * u_resolution.xy)
      / min(u_resolution.x, u_resolution.y);
    vec2 cursorDelta = p - cursor;
    if (u_cursorEffect < 0.5) {
      p += cursor * u_cursorPresence * u_cursorStrength * 0.55;
    } else {
      float cursorDistance = length(cursorDelta);
      vec2 cursorDirection = cursorDelta / max(cursorDistance, 0.0001);
      cursorMask = u_cursorPresence
        * (1.0 - smoothstep(0.0, u_cursorRadius, cursorDistance));
      if (u_cursorEffect < 1.5) {
        p -= cursorDirection * cursorMask * u_cursorStrength * 0.24;
      } else if (u_cursorEffect < 2.5) {
        float cursorAngle = cursorMask * u_cursorStrength * 2.2;
        float cc = cos(cursorAngle), cs = sin(cursorAngle);
        p = cursor + mat2(cc, -cs, cs, cc) * cursorDelta;
      } else if (u_cursorEffect < 3.5) {
        float ripple = sin(
          cursorDistance / max(u_cursorRadius, 0.001) * 18.0 - u_time * 5.0);
        p -= cursorDirection * ripple * cursorMask * u_cursorStrength * 0.07;
      }
    }
  }

  uv = p * min(u_resolution.x, u_resolution.y) / u_resolution.xy + 0.5;
  p *= u_scale;
  if (abs(u_rotate) > 0.0001) {
    float cr = cos(u_rotate), sr = sin(u_rotate);
    p = mat2(cr, -sr, sr, cr) * p;
  }
  p += u_offset;
  if (u_drift > 0.0001)
    p += u_drift * vec2(sin(u_time * 0.31), cos(u_time * 0.23));
  if (u_warp > 0.0) {
    p += u_warp * (vec2(
      fbm(p * u_detail + u_seed),
      fbm(p * u_detail + vec2(5.2, 1.3))) - 0.5);
  }
  vec3 col;
  if (u_blur > 0.0) {
    float e = u_blur;
    float pe = e * u_scale;
    vec2 uvE = vec2(e) * min(u_resolution.x, u_resolution.y) / u_resolution.xy;
    col  = shade(uv, p, u_time) * 0.36;
    col += shade(uv + vec2(uvE.x, 0.0), p + vec2(pe, 0.0), u_time) * 0.16;
    col += shade(uv - vec2(uvE.x, 0.0), p - vec2(pe, 0.0), u_time) * 0.16;
    col += shade(uv + vec2(0.0, uvE.y), p + vec2(0.0, pe), u_time) * 0.16;
    col += shade(uv - vec2(0.0, uvE.y), p - vec2(0.0, pe), u_time) * 0.16;
  } else {
    col = shade(uv, p, u_time);
  }
  if (abs(u_contrast - 1.0) > 0.0001)
    col = (col - 0.5) * u_contrast + 0.5;
  if (abs(u_saturation - 1.0) > 0.0001) {
    float luma = dot(col, vec3(0.299, 0.587, 0.114));
    col = mix(vec3(luma), col, u_saturation);
  }
  if (abs(u_hue) > 0.0001)
    col = hueRotate(col, u_hue);
  if (abs(u_brightness) > 0.0001)
    col += u_brightness;
  if (u_vignette > 0.0001) {
    float vd = length(screenUv - 0.5) * 1.41421356;
    col *= 1.0 - u_vignette * smoothstep(0.35, 1.0, vd);
  }
  if (u_cursorPresence > 0.001 && u_cursorEffect > 3.5)
    col += (vec3(0.18) + col * 0.12) * cursorMask * u_cursorStrength;
  if (u_grain > 0.0001)
    col += (grainHash(
      gl_FragCoord.xy + vec2(u_seed * 17.0, u_seed * 31.0)) - 0.5) * u_grain;
  gl_FragColor = vec4(clamp(col, 0.0, 1.0), 1.0);
}
`;

  const BG_COLORS = new Float32Array([
    0.012, 0.110, 0.149,
    0.106, 0.424, 0.659,
    0.353, 0.824, 0.957,
    0.918, 0.976, 1.000,
    0, 0, 0,
    0, 0, 0,
    0, 0, 0,
    0, 0, 0,
  ]);

  function compileShader(gl, type, source) {
    const shader = gl.createShader(type);
    if (!shader) return null;
    gl.shaderSource(shader, source);
    gl.compileShader(shader);
    if (!gl.getShaderParameter(shader, gl.COMPILE_STATUS)) {
      console.error("Shader compile error:", gl.getShaderInfoLog(shader));
      gl.deleteShader(shader);
      return null;
    }
    return shader;
  }

  function createProgram(gl, vsSrc, fsSrc) {
    const vs = compileShader(gl, gl.VERTEX_SHADER, vsSrc);
    const fs = compileShader(gl, gl.FRAGMENT_SHADER, fsSrc);
    if (!vs || !fs) {
      if (vs) gl.deleteShader(vs);
      if (fs) gl.deleteShader(fs);
      return null;
    }
    const program = gl.createProgram();
    if (!program) {
      gl.deleteShader(vs);
      gl.deleteShader(fs);
      return null;
    }
    gl.attachShader(program, vs);
    gl.attachShader(program, fs);
    gl.linkProgram(program);
    gl.deleteShader(vs);
    gl.deleteShader(fs);
    if (!gl.getProgramParameter(program, gl.LINK_STATUS)) {
      console.error("Program link error:", gl.getProgramInfoLog(program));
      gl.deleteProgram(program);
      return null;
    }
    return program;
  }

  function initMeshDriftBackground(canvas) {
    if (!canvas) return;
    const gl = canvas.getContext("webgl", {
      alpha: false,
      antialias: false,
      depth: false,
      stencil: false,
      preserveDrawingBuffer: false,
      powerPreference: "high-performance",
    });
    if (!gl) return;

    const program = createProgram(gl, BG_VERT, BG_FRAG);
    if (!program) return;

    const positionBuffer = gl.createBuffer();
    if (!positionBuffer) return;
    gl.bindBuffer(gl.ARRAY_BUFFER, positionBuffer);
    gl.bufferData(gl.ARRAY_BUFFER, new Float32Array([-1, -1, 3, -1, -1, 3]), gl.STATIC_DRAW);

    const aPosition = gl.getAttribLocation(program, "a_position");
    gl.enableVertexAttribArray(aPosition);
    gl.vertexAttribPointer(aPosition, 2, gl.FLOAT, false, 0, 0);

    const uColors = gl.getUniformLocation(program, "u_colors[0]") || gl.getUniformLocation(program, "u_colors");
    const uScene = gl.getUniformLocation(program, "u_scene");
    const uShape = gl.getUniformLocation(program, "u_shape");
    const uSurface = gl.getUniformLocation(program, "u_surface");
    const uFinish = gl.getUniformLocation(program, "u_finish");
    const uTransform = gl.getUniformLocation(program, "u_transform");
    const uSpace = gl.getUniformLocation(program, "u_space");
    const uCursor = gl.getUniformLocation(program, "u_cursor");

    gl.useProgram(program);
    if (uColors) gl.uniform3fv(uColors, BG_COLORS);
    if (uShape) gl.uniform4f(uShape, 1.76, 0.86, 0.68, 0.23);
    if (uSurface) gl.uniform4f(uSurface, 1.98, 1.23, -0.08, 1.6);
    if (uFinish) gl.uniform4f(uFinish, 3.07, 0.14, 0.003, 0.04);
    if (uTransform) gl.uniform4f(uTransform, 2046.0, 2.37, 0.14, 1.0);

    const syncSize = () => {
      const rect = canvas.getBoundingClientRect();
      const dpr = Math.min(window.devicePixelRatio || 1, 2);
      const w = Math.max(1, Math.round(rect.width * dpr));
      const h = Math.max(1, Math.round(rect.height * dpr));
      if (canvas.width !== w || canvas.height !== h) {
        canvas.width = w;
        canvas.height = h;
      }
      gl.viewport(0, 0, canvas.width, canvas.height);
    };
    syncSize();
    const ro = new ResizeObserver(syncSize);
    ro.observe(canvas);
    window.addEventListener("resize", syncSize);

    let targetPX = 0;
    let targetPY = 0;
    let pointerX = 0;
    let pointerY = 0;
    let targetPresence = 0;
    let presence = 0;

    window.addEventListener(
      "pointermove",
      (e) => {
        const rect = canvas.getBoundingClientRect();
        if (rect.width <= 0 || rect.height <= 0) return;
        targetPX = ((e.clientX - rect.left) / rect.width) * 2 - 1;
        targetPY = 1 - ((e.clientY - rect.top) / rect.height) * 2;
        targetPresence = 1;
      },
      { passive: true }
    );
    document.addEventListener("pointerleave", () => {
      targetPresence = 0;
    });
    window.addEventListener("blur", () => {
      targetPresence = 0;
    });

    let rafId = null;
    let lastTs = null;
    let elapsed = 0;

    const renderFrame = (ts) => {
      if (document.hidden) {
        rafId = null;
        lastTs = null;
        return;
      }
      const dt = lastTs === null ? 0 : Math.min((ts - lastTs) / 1000, 0.1);
      lastTs = ts;
      elapsed += dt;

      const lerp = 1 - Math.exp(-dt * 10);
      pointerX += (targetPX - pointerX) * lerp;
      pointerY += (targetPY - pointerY) * lerp;
      presence += (targetPresence - presence) * lerp;

      if (uScene) gl.uniform4f(uScene, canvas.width, canvas.height, elapsed * -1.07, 4.0);
      if (uSpace) gl.uniform4f(uSpace, -0.16, -0.01, pointerX, pointerY);
      if (uCursor) gl.uniform4f(uCursor, presence, 0.0, 0.65, 0.51);

      gl.drawArrays(gl.TRIANGLES, 0, 3);
      rafId = window.requestAnimationFrame(renderFrame);
    };

    const startLoop = () => {
      if (rafId === null && !document.hidden) {
        lastTs = null;
        rafId = window.requestAnimationFrame(renderFrame);
      }
    };
    document.addEventListener("visibilitychange", () => {
      if (document.hidden) {
        if (rafId !== null) window.cancelAnimationFrame(rafId);
        rafId = null;
        lastTs = null;
      } else {
        startLoop();
      }
    });
    startLoop();
  }

  // =========================================================================
  // 2. GALAKSI 3D SPECIMEN & SCROLL ENGINE
  // =========================================================================
  const HEART = [0, 0.25, 0];
  const EMBLEM_RADIUS = 1.06;
  const STRIDE = 6;

  const sub = (a, b) => [a[0] - b[0], a[1] - b[1], a[2] - b[2]];
  const dot = (a, b) => a[0] * b[0] + a[1] * b[1] + a[2] * b[2];
  const cross = (a, b) => [
    a[1] * b[2] - a[2] * b[1],
    a[2] * b[0] - a[0] * b[2],
    a[0] * b[1] - a[1] * b[0],
  ];
  const norm = (a) => {
    const l = Math.hypot(a[0], a[1], a[2]) || 1;
    return [a[0] / l, a[1] / l, a[2] / l];
  };

  function buildEmblemAssets(img) {
    const W = img.naturalWidth || 569;
    const H = img.naturalHeight || 569;

    const srcCanvas = document.createElement("canvas");
    srcCanvas.width = W;
    srcCanvas.height = H;
    const sCtx = srcCanvas.getContext("2d");
    sCtx.clearRect(0, 0, W, H);
    sCtx.drawImage(img, 0, 0, W, H);

    const imgData = sCtx.getImageData(0, 0, W, H);
    const px = imgData.data;
    const totalPx = W * H;
    const bg = new Uint8Array(totalPx);
    const queue = new Int32Array(totalPx);

    const isEdgeWhite = (idx, minThr, satMax) => {
      const i4 = idx * 4;
      const a = px[i4 + 3];
      if (a < 32) return true;
      const r = px[i4];
      const g = px[i4 + 1];
      const b = px[i4 + 2];
      const mn = Math.min(r, g, b);
      const mx = Math.max(r, g, b);
      return mn > minThr && mx - mn < satMax;
    };

    let head = 0;
    let tail = 0;
    const markBg = (idx) => {
      if (bg[idx]) return;
      bg[idx] = 1;
      queue[tail++] = idx;
    };

    for (let x = 0; x < W; x++) {
      if (isEdgeWhite(x, 236, 24)) markBg(x);
      const bot = (H - 1) * W + x;
      if (isEdgeWhite(bot, 236, 24)) markBg(bot);
    }
    for (let y = 0; y < H; y++) {
      const left = y * W;
      const right = left + W - 1;
      if (isEdgeWhite(left, 236, 24)) markBg(left);
      if (isEdgeWhite(right, 236, 24)) markBg(right);
    }
    while (head < tail) {
      const cur = queue[head++];
      const cx = cur % W;
      const cy = (cur - cx) / W;
      if (cx > 0 && isEdgeWhite(cur - 1, 236, 24)) markBg(cur - 1);
      if (cx < W - 1 && isEdgeWhite(cur + 1, 236, 24)) markBg(cur + 1);
      if (cy > 0 && isEdgeWhite(cur - W, 236, 24)) markBg(cur - W);
      if (cy < H - 1 && isEdgeWhite(cur + W, 236, 24)) markBg(cur + W);
    }

    const seen = new Uint8Array(totalPx);
    const compQueue = new Int32Array(totalPx);
    const isPureNeutralWhite = (idx, thr) => {
      const i4 = idx * 4;
      const r = px[i4];
      const g = px[i4 + 1];
      const b = px[i4 + 2];
      return Math.min(r, g, b) >= thr && Math.max(r, g, b) - Math.min(r, g, b) < 18;
    };

    for (let y = 0; y < Math.floor(H * 0.34); y++) {
      for (let x = 0; x < W; x++) {
        const start = y * W + x;
        if (bg[start] || seen[start]) continue;
        if (!isPureNeutralWhite(start, 246)) continue;
        let cHead = 0;
        let cTail = 0;
        seen[start] = 1;
        compQueue[cTail++] = start;
        while (cHead < cTail) {
          const cur = compQueue[cHead++];
          const cx = cur % W;
          const cy = (cur - cx) / W;
          const neighbors = [
            cx > 0 ? cur - 1 : -1,
            cx < W - 1 ? cur + 1 : -1,
            cy > 0 ? cur - W : -1,
            cy < H - 1 ? cur + W : -1,
          ];
          for (let n = 0; n < 4; n++) {
            const nb = neighbors[n];
            if (nb >= 0 && !bg[nb] && !seen[nb] && isPureNeutralWhite(nb, 242)) {
              seen[nb] = 1;
              compQueue[cTail++] = nb;
            }
          }
        }
        if (cTail >= 140) {
          for (let k = 0; k < cTail; k++) bg[compQueue[k]] = 1;
        }
      }
    }

    for (let y = 0; y < H; y++) {
      for (let x = 0; x < W; x++) {
        const idx = y * W + x;
        const i4 = idx * 4;
        if (bg[idx]) {
          px[i4 + 3] = 0;
          continue;
        }
        let bgNeighbors = 0;
        if (x > 0 && bg[idx - 1]) bgNeighbors++;
        if (x < W - 1 && bg[idx + 1]) bgNeighbors++;
        if (y > 0 && bg[idx - W]) bgNeighbors++;
        if (y < H - 1 && bg[idx + W]) bgNeighbors++;
        if (bgNeighbors > 0) {
          const r = px[i4];
          const g = px[i4 + 1];
          const b = px[i4 + 2];
          const lum = (r + g + b) / 3;
          const sat = Math.max(r, g, b) - Math.min(r, g, b);
          if (lum > 185 && sat < 35) {
            const factor = Math.max(0.25, 1 - (lum - 185) / 85);
            px[i4 + 3] = Math.round(255 * factor);
            px[i4] = Math.round(r * 0.78);
            px[i4 + 1] = Math.round(g * 0.66);
            px[i4 + 2] = Math.round(b * 0.42);
          }
        }
      }
    }
    sCtx.putImageData(imgData, 0, 0);

    const TEX_SIZE = 1024;
    const texCanvas = document.createElement("canvas");
    texCanvas.width = TEX_SIZE;
    texCanvas.height = TEX_SIZE;
    const tCtx = texCanvas.getContext("2d");
    tCtx.imageSmoothingEnabled = true;
    tCtx.imageSmoothingQuality = "high";
    tCtx.clearRect(0, 0, TEX_SIZE, TEX_SIZE);
    tCtx.drawImage(srcCanvas, 0, 0, TEX_SIZE, TEX_SIZE);

    const R_SIZE = 256;
    const reliefCanvas = document.createElement("canvas");
    reliefCanvas.width = R_SIZE;
    reliefCanvas.height = R_SIZE;
    const rCtx = reliefCanvas.getContext("2d");
    rCtx.imageSmoothingEnabled = true;
    rCtx.imageSmoothingQuality = "high";
    rCtx.drawImage(srcCanvas, 0, 0, R_SIZE, R_SIZE);
    const rData = rCtx.getImageData(0, 0, R_SIZE, R_SIZE);
    const rp = rData.data;

    const hBuf = new Float32Array(R_SIZE * R_SIZE);
    const hTmp = new Float32Array(R_SIZE * R_SIZE);
    for (let i = 0; i < R_SIZE * R_SIZE; i++) {
      const i4 = i * 4;
      const a = rp[i4 + 3] / 255;
      const r = rp[i4] / 255;
      const g = rp[i4 + 1] / 255;
      const b = rp[i4 + 2] / 255;
      const lum = 0.299 * r + 0.587 * g + 0.114 * b;
      const gold = Math.max(0, r - b);
      hBuf[i] = a * (0.45 + 0.35 * lum + 0.35 * gold);
    }

    const blurPass = (src, dst, rad) => {
      const tmp = new Float32Array(R_SIZE * R_SIZE);
      const inv = 1 / (rad * 2 + 1);
      for (let y = 0; y < R_SIZE; y++) {
        const row = y * R_SIZE;
        for (let x = 0; x < R_SIZE; x++) {
          let sum = 0;
          for (let k = -rad; k <= rad; k++) {
            const sx = Math.max(0, Math.min(R_SIZE - 1, x + k));
            sum += src[row + sx];
          }
          tmp[row + x] = sum * inv;
        }
      }
      for (let y = 0; y < R_SIZE; y++) {
        for (let x = 0; x < R_SIZE; x++) {
          let sum = 0;
          for (let k = -rad; k <= rad; k++) {
            const sy = Math.max(0, Math.min(R_SIZE - 1, y + k));
            sum += tmp[sy * R_SIZE + x];
          }
          dst[y * R_SIZE + x] = sum * inv;
        }
      }
    };
    blurPass(hBuf, hTmp, 2);
    blurPass(hTmp, hBuf, 2);

    for (let y = 0; y < R_SIZE; y++) {
      for (let x = 0; x < R_SIZE; x++) {
        const i = y * R_SIZE + x;
        const hL = hBuf[y * R_SIZE + Math.max(0, x - 1)];
        const hR = hBuf[y * R_SIZE + Math.min(R_SIZE - 1, x + 1)];
        const hU = hBuf[Math.max(0, y - 1) * R_SIZE + x];
        const hD = hBuf[Math.min(R_SIZE - 1, y + 1) * R_SIZE + x];
        const nx = Math.max(-1, Math.min(1, (hL - hR) * 4.5));
        const ny = Math.max(-1, Math.min(1, (hD - hU) * 4.5));
        const i4 = i * 4;
        rp[i4] = Math.round((nx * 0.5 + 0.5) * 255);
        rp[i4 + 1] = Math.round((ny * 0.5 + 0.5) * 255);
        rp[i4 + 2] = Math.round(Math.min(1, hBuf[i]) * 255);
        rp[i4 + 3] = 255;
      }
    }
    rCtx.putImageData(rData, 0, 0);

    const SLICES = 38;
    const HALF_DEPTH = 0.068;
    const verts = [];
    const idx = [];
    const y0 = HEART[1] - 1.0;
    const y1 = HEART[1] + 1.0;

    for (let s = 0; s < SLICES; s++) {
      const t = s / (SLICES - 1);
      const layer = t * 2 - 1;
      const z = layer * HALF_DEPTH;
      const baseV = s * 4;

      verts.push(-1, y0, z, 0, 1, layer);
      verts.push(1, y0, z, 1, 1, layer);
      verts.push(1, y1, z, 1, 0, layer);
      verts.push(-1, y1, z, 0, 0, layer);

      if (s === 0) {
        idx.push(baseV + 0, baseV + 2, baseV + 1, baseV + 0, baseV + 3, baseV + 2);
      } else {
        idx.push(baseV + 0, baseV + 1, baseV + 2, baseV + 0, baseV + 2, baseV + 3);
      }
    }

    return {
      data: new Float32Array(verts),
      index: new Uint16Array(idx),
      texCanvas,
      reliefCanvas,
    };
  }

  const clamp01 = (x) => (x <= 0 ? 0 : x > 1 ? 1 : x);
  const easeInOut = (t) => (t < 0.5 ? 4 * t * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2);

  const progressFrom = (top, height, viewport) => {
    const travel = height - viewport;
    if (travel <= 0) return 0;
    return clamp01(-top / travel);
  };

  const sceneCoord = (p, n, hold) => {
    if (n <= 1) return 0;
    const t = clamp01(p) * (n - 1);
    const i = Math.min(Math.floor(t), n - 2);
    const h = hold / 2;
    return i + easeInOut(clamp01((t - i - h) / (1 - 2 * h)));
  };

  const reveal = (coord, scene, delay) => {
    const d = coord - scene;
    const lag = d < 0 ? delay : 1 - delay;
    return clamp01((0.6 - Math.abs(d) - lag * 0.2) / 0.25);
  };

  const KEYS = [
    { spin: -0.2, el: 7, size: 0.6, ox: 0, oy: 0.04 },
    { spin: 0.24, el: 10, size: 0.5, ox: 0.44, oy: 0.02 },
    { spin: -0.22, el: 6, size: 0.5, ox: 0.44, oy: 0.02 },
    { spin: 0.18, el: 9, size: 0.5, ox: 0.44, oy: 0.02 },
    { spin: 0.22, el: -12, size: 0.42, ox: 0, oy: -0.36 },
    { spin: -0.38, el: 10, size: 0.74, ox: 0.52, oy: 0.06 },
    { spin: 0.22, el: -12, size: 0.5, ox: 0, oy: 0.44 },
  ];

  const KEYS_TALL = [
    { size: 0.80, ox: 0, oy: 0.04 },
    { size: 0.48, ox: 0, oy: 0.56 },
    { size: 0.48, ox: 0, oy: 0.56 },
    { size: 0.48, ox: 0, oy: 0.56 },
    { size: 0.58, ox: 0, oy: -0.44 },
    { size: 0.92, ox: 0.22, oy: 0.42 },
    { size: 0.46, ox: 0, oy: 0.64 },
  ];

  const SCENES = KEYS.length;
  const HOLD = 0.34;

  const keyAt = (coord, tall) => {
    const i = Math.max(0, Math.min(SCENES - 1, Math.floor(coord)));
    const j = Math.min(SCENES - 1, i + 1);
    const f = coord - i;
    const a = Object.assign({}, KEYS[i], tall ? KEYS_TALL[i] : {});
    const b = Object.assign({}, KEYS[j], tall ? KEYS_TALL[j] : {});
    const l = (x, y) => x + (y - x) * f;
    return {
      spin: l(a.spin, b.spin),
      el: l(a.el, b.el),
      size: l(a.size, b.size),
      ox: l(a.ox, b.ox),
      oy: l(a.oy, b.oy),
    };
  };

  const perspective = (fovy, aspect, near, far) => {
    const f = 1 / Math.tan(fovy / 2);
    const m = new Float32Array(16);
    m[0] = f / aspect;
    m[5] = f;
    m[10] = (far + near) / (near - far);
    m[11] = -1;
    m[14] = (2 * far * near) / (near - far);
    return m;
  };

  const lookAt = (eye, at) => {
    const z = norm(sub(eye, at));
    const x = norm(cross([0, 1, 0], z));
    const y = cross(z, x);
    const m = new Float32Array(16);
    m[0] = x[0]; m[4] = x[1]; m[8] = x[2];
    m[1] = y[0]; m[5] = y[1]; m[9] = y[2];
    m[2] = z[0]; m[6] = z[1]; m[10] = z[2];
    m[12] = -dot(x, eye); m[13] = -dot(y, eye); m[14] = -dot(z, eye); m[15] = 1;
    return m;
  };

  const multiply = (a, b) => {
    const o = new Float32Array(16);
    for (let c = 0; c < 4; c++) {
      for (let r = 0; r < 4; r++) {
        o[c * 4 + r] =
          a[r] * b[c * 4] +
          a[4 + r] * b[c * 4 + 1] +
          a[8 + r] * b[c * 4 + 2] +
          a[12 + r] * b[c * 4 + 3];
      }
    }
    return o;
  };

  const rotY = (t) => {
    const c = Math.cos(t);
    const s = Math.sin(t);
    return new Float32Array([c, 0, -s, 0, 0, 1, 0, 0, s, 0, c, 0, 0, 0, 0, 1]);
  };

  const rotX = (t) => {
    const c = Math.cos(t);
    const s = Math.sin(t);
    return new Float32Array([1, 0, 0, 0, 0, c, s, 0, 0, -s, c, 0, 0, 0, 0, 1]);
  };

  const EMBLEM_VERT = `
attribute vec3 a_pos;
attribute vec2 a_uv;
attribute float a_layer;
uniform mat4 u_vp;
uniform mat4 u_model;
uniform vec2 u_offset;
uniform vec3 u_heart;
uniform float u_time;
uniform float u_bloom;
uniform float u_sway;
varying vec3 v_w;
varying vec3 v_nBase;
varying vec2 v_uv;
varying float v_layer;
void main() {
  float g = 1.0 - pow(1.0 - clamp(u_bloom, 0.0, 1.0), 3.0);
  float s = mix(0.8, 1.0, g);
  vec3 p = u_heart + (a_pos - u_heart) * s;
  p.y += u_sway * sin(u_time * 0.9) * 0.022;
  vec4 w = u_model * vec4(p - u_heart, 1.0);
  w.xyz += u_heart;
  v_w = w.xyz;
  vec3 baseN = a_layer < -0.96 ? vec3(0.0, 0.0, -1.0) : vec3(0.0, 0.0, 1.0);
  v_nBase = (u_model * vec4(baseN, 0.0)).xyz;
  v_uv = a_uv;
  v_layer = a_layer;
  gl_Position = u_vp * w;
  gl_Position.xy += u_offset * gl_Position.w;
}
`;

  const EMBLEM_FRAG = `
precision highp float;
uniform sampler2D u_tex;
uniform sampler2D u_reliefTex;
uniform mat4 u_model;
uniform vec3 u_eye;
uniform float u_time;
uniform float u_alpha;
varying vec3 v_w;
varying vec3 v_nBase;
varying vec2 v_uv;
varying float v_layer;

float studio(vec3 r) {
  float key = pow(max(dot(r, normalize(vec3(-0.35, 0.8, 0.5))), 0.0), 18.0) * 1.8;
  float strip = smoothstep(0.45, 0.85, r.x) * smoothstep(-0.6, 0.5, r.y) * 0.9;
  float rim = smoothstep(0.4, 0.92, -r.z) * smoothstep(-0.3, 0.6, r.y) * 0.7;
  return key + strip + rim;
}

void main() {
  vec4 tex = texture2D(u_tex, v_uv);
  vec4 rel = texture2D(u_reliefTex, v_uv);

  bool isFront = v_layer > 0.96;
  bool isBack = v_layer < -0.96;

  if (isFront) {
    if (tex.a < 0.06 || !gl_FrontFacing) discard;
  } else if (isBack) {
    if (tex.a < 0.42 || !gl_FrontFacing) discard;
  } else {
    if (tex.a < 0.48) discard;
  }

  vec3 viewDir = normalize(u_eye - v_w);
  vec3 slope = vec3((rel.r - 0.5) * 2.0, (rel.g - 0.5) * 2.0, 0.0);
  vec3 nLocal = isBack
    ? normalize(vec3(-slope.x * 0.35, slope.y * 0.35, -1.0))
    : isFront
      ? normalize(vec3(slope.x * 0.42, slope.y * 0.42, 1.0))
      : normalize(vec3(slope.x, slope.y, 0.08 * v_layer));
  vec3 n = normalize((u_model * vec4(nLocal, 0.0)).xyz);
  if (dot(n, viewDir) < 0.0) n = -n;

  vec3 refl = reflect(-viewDir, n);
  float spec = studio(refl);
  float fresnel = pow(1.0 - max(dot(n, viewDir), 0.0), 3.0);
  float diff = max(dot(n, normalize(vec3(-0.25, 0.65, 0.72))), 0.0);

  vec3 gold = vec3(0.86, 0.64, 0.22);
  vec3 goldHot = vec3(1.0, 0.93, 0.68);

  if (isFront) {
    vec3 albedo = tex.rgb;
    float lum = dot(albedo, vec3(0.299, 0.587, 0.114));
    float goldMask = clamp((albedo.r - albedo.b) * 1.6, 0.0, 1.0);
    float metallic = smoothstep(0.22, 0.78, lum * 0.65 + goldMask * 0.55);

    float sweepPos = sin(u_time * 0.85) * 0.9;
    float sweep = exp(-pow((v_uv.x - v_uv.y * 0.55) - sweepPos, 2.0) * 16.0);

    vec3 lit = albedo * (0.82 + 0.24 * diff);
    lit += goldHot * spec * (0.08 + 0.28 * metallic);
    lit += goldHot * sweep * goldMask * 0.16;
    lit += gold * fresnel * 0.22;

    float a = smoothstep(0.06, 0.50, tex.a) * u_alpha;
    gl_FragColor = vec4(clamp(lit, 0.0, 1.0) * a, a);
  } else if (isBack) {
    vec2 mUv = vec2(1.0 - v_uv.x, v_uv.y);
    vec4 backTex = texture2D(u_tex, mUv);
    float crest = dot(backTex.rgb, vec3(0.299, 0.587, 0.114));
    vec3 navy = vec3(0.04, 0.11, 0.22);
    vec3 backCol = mix(navy, gold * 0.72, smoothstep(0.35, 0.88, crest) * 0.75);
    backCol = backCol * (0.55 + 0.45 * diff) + gold * spec * 0.45 + goldHot * fresnel * 0.35;
    gl_FragColor = vec4(clamp(backCol, 0.0, 1.0) * u_alpha, u_alpha);
  } else {
    float ridge = 0.84 + 0.16 * cos(v_layer * 3.14159);
    vec3 sideCol = gold * ridge * (0.48 + 0.62 * diff);
    sideCol += goldHot * spec * 0.65;
    sideCol += goldHot * fresnel * 0.42;
    gl_FragColor = vec4(clamp(sideCol, 0.0, 1.0) * u_alpha, u_alpha);
  }
}
`;

  function initGalaksiSpecimen(root) {
    if (!root) return;
    const stage = root.querySelector(".lys-stage");
    const canvas = root.querySelector(".lys-gl-canvas");
    const glow = root.querySelector(".lys-glow");
    if (!stage || !canvas || !glow) return;

    const imageSrc = root.dataset.emblemSrc || "/assets/aistudio/logo_GALAKSI_NEW_c6e4942a.png";
    const mq = window.matchMedia("(prefers-reduced-motion: reduce)");
    let reduced = mq.matches;
    mq.addEventListener("change", () => {
      reduced = mq.matches;
    });

    const bgWrap = document.querySelector(".galaksi-bg-wrap");
    const scenePanels = Array.from(stage.querySelectorAll("[data-scene-panel]"));
    const navButtons = Array.from(stage.querySelectorAll(".lys-nav button[data-jump]"));

    const setActiveScene = (idx) => {
      if (bgWrap) {
        const showBgShades = idx === 0 || idx === SCENES - 1;
        bgWrap.classList.toggle("is-hidden", !showBgShades);
      }
      scenePanels.forEach((panel) => {
        const isCurrent = Number(panel.dataset.scenePanel) === idx;
        panel.classList.toggle("is-active", isCurrent);
      });
      navButtons.forEach((btn) => {
        const isCurrent = Number(btn.dataset.jump) === idx;
        if (isCurrent) btn.setAttribute("aria-current", "step");
        else btn.removeAttribute("aria-current");
      });
    };

    // Initial entrance on Cover (0)
    requestAnimationFrame(() => setActiveScene(0));

    // Jump buttons
    navButtons.forEach((btn) => {
      btn.addEventListener("click", () => {
        const i = Number(btn.dataset.jump);
        const r = root.getBoundingClientRect();
        const travel = r.height - stage.clientHeight;
        const targetTop = r.top + window.scrollY;
        const p = SCENES <= 1 ? 0 : i / (SCENES - 1);
        window.scrollTo({
          top: targetTop + p * travel,
          behavior: reduced ? "auto" : "smooth",
        });
      });
    });

    // Interactive hints & copy buttons
    const ticketBtn = root.querySelector("[data-ticket-btn]");
    const ticketHintEl = root.querySelector("[data-ticket-hint]");
    if (ticketBtn && ticketHintEl) {
      ticketBtn.addEventListener("click", (e) => {
        const href = ticketBtn.getAttribute("href") || "";
        if (!/^https?:\/\//.test(href) && !href.startsWith("/")) {
          e.preventDefault();
          const show = ticketHintEl.hasAttribute("hidden");
          if (show) {
            ticketHintEl.removeAttribute("hidden");
            ticketBtn.setAttribute("data-active", "true");
          } else {
            ticketHintEl.setAttribute("hidden", "");
            ticketBtn.removeAttribute("data-active");
          }
        }
      });
    }

    let activePartner = null;
    const partnerHintEl = root.querySelector("[data-partner-hint]");
    const partnerTriggers = Array.from(root.querySelectorAll("[data-partner-trigger]"));
    const syncPartnerUI = () => {
      partnerTriggers.forEach((el) => {
        if (el.classList.contains("lys-btn")) {
          if (el.dataset.partnerTrigger === activePartner) el.setAttribute("data-active", "true");
          else el.removeAttribute("data-active");
        }
      });
      if (partnerHintEl) {
        if (activePartner === "tenant") {
          partnerHintEl.textContent = "Tautan info booth tenant dapat diatur melalui $tenantUrl di route Laravel.";
          partnerHintEl.removeAttribute("hidden");
        } else if (activePartner === "sponsor") {
          partnerHintEl.textContent = "Tautan info kemitraan sponsor dapat diatur melalui $sponsorUrl di route Laravel.";
          partnerHintEl.removeAttribute("hidden");
        } else {
          partnerHintEl.setAttribute("hidden", "");
        }
      }
    };

    partnerTriggers.forEach((el) => {
      el.addEventListener("click", (e) => {
        const href = el.getAttribute("href") || "";
        if (!/^https?:\/\//.test(href) && !href.startsWith("/")) {
          e.preventDefault();
          const kind = el.dataset.partnerTrigger;
          activePartner = activePartner === kind ? null : kind;
          syncPartnerUI();
        }
      });
    });

    const socialBtns = Array.from(root.querySelectorAll("button[data-social-handle]"));
    socialBtns.forEach((btn) => {
      btn.addEventListener("click", () => {
        const handle = btn.dataset.socialHandle || "";
        const platform = btn.dataset.socialPlatform || "";
        if (navigator.clipboard && handle) {
          navigator.clipboard.writeText(handle).catch(() => {});
        }
        socialBtns.forEach((b) => b.classList.remove("is-copied"));
        btn.classList.add("is-copied");
        btn.title = "Tersalin: " + handle;
        setTimeout(() => {
          btn.classList.remove("is-copied");
          btn.title = platform;
        }, 2200);
      });
    });

    // WebGL setup for 3D emblem
    let gl = null;
    let ctx2d = null;
    let prog = null;
    let vbo = null;
    let ibo = null;
    let texMain = null;
    let texRelief = null;
    let indexCount = 0;
    let assets = null;
    const loc = {};

    const uploadAssets = () => {
      if (!gl || !prog || !assets) return;
      gl.useProgram(prog);

      if (!vbo) vbo = gl.createBuffer();
      gl.bindBuffer(gl.ARRAY_BUFFER, vbo);
      gl.bufferData(gl.ARRAY_BUFFER, assets.data, gl.STATIC_DRAW);

      if (!ibo) ibo = gl.createBuffer();
      gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER, ibo);
      gl.bufferData(gl.ELEMENT_ARRAY_BUFFER, assets.index, gl.STATIC_DRAW);
      indexCount = assets.index.length;

      const attr = (name, size, off) => {
        const a = gl.getAttribLocation(prog, name);
        if (a < 0) return;
        gl.enableVertexAttribArray(a);
        gl.vertexAttribPointer(a, size, gl.FLOAT, false, STRIDE * 4, off * 4);
      };
      attr("a_pos", 3, 0);
      attr("a_uv", 2, 3);
      attr("a_layer", 1, 5);

      const anisoExt =
        gl.getExtension("EXT_texture_filter_anisotropic") ||
        gl.getExtension("WEBKIT_EXT_texture_filter_anisotropic") ||
        gl.getExtension("MOZ_EXT_texture_filter_anisotropic");

      if (!texMain) texMain = gl.createTexture();
      gl.activeTexture(gl.TEXTURE0);
      gl.bindTexture(gl.TEXTURE_2D, texMain);
      gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_WRAP_S, gl.CLAMP_TO_EDGE);
      gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_WRAP_T, gl.CLAMP_TO_EDGE);
      gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MIN_FILTER, gl.LINEAR_MIPMAP_LINEAR);
      gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MAG_FILTER, gl.LINEAR);
      if (anisoExt) {
        const maxAniso = gl.getParameter(anisoExt.MAX_TEXTURE_MAX_ANISOTROPY_EXT) || 4;
        gl.texParameterf(gl.TEXTURE_2D, anisoExt.TEXTURE_MAX_ANISOTROPY_EXT, Math.min(8, maxAniso));
      }
      gl.texImage2D(gl.TEXTURE_2D, 0, gl.RGBA, gl.RGBA, gl.UNSIGNED_BYTE, assets.texCanvas);
      gl.generateMipmap(gl.TEXTURE_2D);
      if (loc.u_tex) gl.uniform1i(loc.u_tex, 0);

      if (!texRelief) texRelief = gl.createTexture();
      gl.activeTexture(gl.TEXTURE1);
      gl.bindTexture(gl.TEXTURE_2D, texRelief);
      gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_WRAP_S, gl.CLAMP_TO_EDGE);
      gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_WRAP_T, gl.CLAMP_TO_EDGE);
      gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MIN_FILTER, gl.LINEAR);
      gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MAG_FILTER, gl.LINEAR);
      gl.texImage2D(gl.TEXTURE_2D, 0, gl.RGBA, gl.RGBA, gl.UNSIGNED_BYTE, assets.reliefCanvas);
      if (loc.u_reliefTex) gl.uniform1i(loc.u_reliefTex, 1);
    };

    const initGL = () => {
      gl = canvas.getContext("webgl", { alpha: true, antialias: true, premultipliedAlpha: true });
      if (!gl) return false;
      prog = createProgram(gl, EMBLEM_VERT, EMBLEM_FRAG);
      if (!prog) return false;
      gl.useProgram(prog);
      const names = ["u_vp", "u_model", "u_offset", "u_heart", "u_time", "u_bloom", "u_sway", "u_eye", "u_tex", "u_reliefTex", "u_alpha"];
      names.forEach((n) => {
        loc[n] = gl.getUniformLocation(prog, n);
      });
      gl.enable(gl.DEPTH_TEST);
      gl.depthFunc(gl.LEQUAL);
      gl.enable(gl.BLEND);
      gl.blendFunc(gl.ONE, gl.ONE_MINUS_SRC_ALPHA);
      gl.clearColor(0, 0, 0, 0);
      if (assets) uploadAssets();
      return true;
    };

    if (!initGL()) {
      gl = null;
      ctx2d = canvas.getContext("2d");
    }

    const img = new Image();
    img.crossOrigin = "anonymous";
    img.onload = () => {
      assets = buildEmblemAssets(img);
      if (gl && prog) uploadAssets();
    };
    img.src = imageSrc;

    canvas.addEventListener("webglcontextlost", (e) => {
      e.preventDefault();
      gl = null;
    });
    canvas.addEventListener("webglcontextrestored", () => {
      vbo = null;
      ibo = null;
      texMain = null;
      texRelief = null;
      if (!initGL()) gl = null;
    });

    let W = 1;
    let H = 1;
    let dpr = 1;
    const measure = () => {
      const sr = stage.getBoundingClientRect();
      W = Math.max(1, sr.width);
      H = Math.max(1, sr.height);
      dpr = Math.min(window.devicePixelRatio || 1, 2);
      const cw = Math.round(W * dpr);
      const ch = Math.round(H * dpr);
      if (canvas.width !== cw || canvas.height !== ch) {
        canvas.width = cw;
        canvas.height = ch;
      }
    };
    const ro = new ResizeObserver(measure);
    ro.observe(stage);
    measure();

    const items = Array.from(stage.querySelectorAll("[data-sc]")).map((el) => ({
      el,
      scene: Number(el.dataset.sc),
      fx: el.dataset.fx || "rise",
      delay: Number(el.dataset.d) || 0,
      last: -1,
    }));

    const pointer = { x: 0, y: 0, tx: 0, ty: 0 };
    let spin = 0;
    let spinVel = 0;
    let drag = null;

    stage.addEventListener("pointermove", (e) => {
      const r = stage.getBoundingClientRect();
      pointer.tx = ((e.clientX - r.left) / r.width) * 2 - 1;
      pointer.ty = ((e.clientY - r.top) / r.height) * 2 - 1;
      if (drag && drag.id === e.pointerId) {
        spinVel += (e.clientX - drag.x) * 0.004;
        drag.x = e.clientX;
      }
    });
    stage.addEventListener("pointerdown", (e) => {
      if (e.pointerType !== "mouse" || e.button !== 0) return;
      if (e.target && e.target.closest("a,button")) return;
      drag = { id: e.pointerId, x: e.clientX };
      stage.style.cursor = "grabbing";
    });
    window.addEventListener("pointerup", () => {
      drag = null;
      stage.style.cursor = "";
    });

    let raf = 0;
    let running = false;
    let visible = true;
    let last = performance.now();
    const born = last;
    let time = 0;
    let prog01 = -1;
    let shownScene = 0;

    const frame = (now) => {
      raf = requestAnimationFrame(frame);
      const dt = Math.min(0.05, (now - last) / 1000);
      last = now;
      const moving = !reduced;
      if (moving) time += dt;

      const r = root.getBoundingClientRect();
      const p = progressFrom(r.top, r.height, H);
      prog01 = prog01 < 0 || reduced ? p : prog01 + (p - prog01) * (1 - Math.exp(-dt * 9));
      const coord = sceneCoord(prog01, SCENES, HOLD);
      const scene = Math.round(coord);
      if (scene !== shownScene) {
        shownScene = scene;
        setActiveScene(scene);
      }

      const tall = H > W * 1.05;
      const k = keyAt(coord, tall);
      pointer.x += (pointer.tx - pointer.x) * (1 - Math.exp(-dt * 4));
      pointer.y += (pointer.ty - pointer.y) * (1 - Math.exp(-dt * 4));
      if (moving) {
        spin += spinVel;
        spinVel *= Math.exp(-dt * 4);
        if (!drag) spin *= Math.exp(-dt * 0.9);
      } else {
        spin += spinVel;
        spinVel = 0;
      }

      const px = moving ? pointer.x : 0;
      const py = moving ? pointer.y : 0;
      const idleTurn = moving ? Math.sin(time * 0.55) * 0.14 : 0;
      const fov = (30 * Math.PI) / 180;
      const minDim = Math.min(W, H);
      const dist = (EMBLEM_RADIUS * H) / (k.size * minDim * Math.tan(fov / 2));
      const elAngle = Math.max(-88, Math.min(88, k.el + py * 11)) * (Math.PI / 180);
      const eye = [HEART[0], HEART[1] + Math.sin(elAngle) * dist, HEART[2] + Math.cos(elAngle) * dist];
      const proj = perspective(fov, W / H, Math.max(0.05, dist - 6), dist + 8);
      const vp = multiply(proj, lookAt(eye, HEART));
      const model = multiply(rotY(k.spin + spin + idleTurn + px * 0.34), rotX(py * 0.08));
      const bloom = reduced ? 1 : clamp01((now - born) / 1400);

      const gx = (0.5 + k.ox / 2) * W;
      const gy = (0.5 - k.oy / 2) * H;
      const gr = k.size * minDim * 1.18;
      glow.style.transform = "translate(" + (gx - gr).toFixed(1) + "px," + (gy - gr).toFixed(1) + "px)";
      glow.style.width = glow.style.height = (gr * 2).toFixed(1) + "px";

      if (gl && prog && indexCount > 0) {
        gl.viewport(0, 0, canvas.width, canvas.height);
        gl.clear(gl.COLOR_BUFFER_BIT | gl.DEPTH_BUFFER_BIT);
        gl.uniformMatrix4fv(loc.u_vp, false, vp);
        gl.uniformMatrix4fv(loc.u_model, false, model);
        gl.uniform2f(loc.u_offset, k.ox, k.oy);
        gl.uniform3f(loc.u_heart, HEART[0], HEART[1], HEART[2]);
        gl.uniform1f(loc.u_time, time);
        gl.uniform1f(loc.u_bloom, bloom);
        gl.uniform1f(loc.u_sway, moving ? 1 : 0);
        gl.uniform3f(loc.u_eye, eye[0], eye[1], eye[2]);
        gl.uniform1f(loc.u_alpha, 1);
        gl.drawElements(gl.TRIANGLES, indexCount, gl.UNSIGNED_SHORT, 0);
      } else if (ctx2d && assets) {
        ctx2d.setTransform(dpr, 0, 0, dpr, 0, 0);
        ctx2d.clearRect(0, 0, W, H);
        const s = minDim * k.size * 1.75;
        const tiltX = Math.max(0.15, Math.abs(Math.cos(k.spin + spin + idleTurn + px * 0.34)));
        ctx2d.save();
        ctx2d.translate(gx, gy);
        ctx2d.scale(tiltX, 1);
        ctx2d.drawImage(assets.texCanvas, -s / 2, -s / 2, s, s);
        ctx2d.restore();
      }

      for (let idxItem = 0; idxItem < items.length; idxItem++) {
        const it = items[idxItem];
        const v = reveal(coord, it.scene, it.delay);
        const q = Math.round(v * 500) / 500;
        if (q === it.last) continue;
        it.last = q;
        const s = it.el.style;
        const dir = coord < it.scene ? 1 : -1;
        s.visibility = q <= 0 ? "hidden" : "";
        if (it.fx === "line") {
          s.transform = "scaleX(" + q + ")";
          s.opacity = String(Math.min(1, q * 2));
          continue;
        }
        s.opacity = String(q);
        if (reduced || it.fx === "fade") continue;
        if (it.fx === "clip") {
          if (q > 0.995) {
            s.clipPath = "";
            s.transform = "";
          } else {
            const hid = ((1 - q) * 100).toFixed(1);
            s.clipPath = dir > 0
              ? "inset(-0.2em -0.1em " + hid + "% -0.1em)"
              : "inset(" + hid + "% -0.1em -0.2em -0.1em)";
            s.transform = "translateY(" + ((1 - q) * 0.4 * dir).toFixed(3) + "em)";
          }
        } else {
          s.transform = "translateY(" + ((1 - q) * 34 * dir).toFixed(1) + "px)";
          s.filter = q > 0.995 ? "" : "blur(" + ((1 - q) * 8).toFixed(1) + "px)";
        }
      }
    };

    const start = () => {
      if (running || !visible || document.hidden) return;
      running = true;
      last = performance.now();
      raf = requestAnimationFrame(frame);
    };
    const stop = () => {
      running = false;
      cancelAnimationFrame(raf);
    };

    const io = new IntersectionObserver((entries) => {
      visible = entries[0] ? entries[0].isIntersecting : true;
      if (visible) start();
      else stop();
    });
    io.observe(root);
    document.addEventListener("visibilitychange", () => (document.hidden ? stop() : start()));
    start();
  }

  document.addEventListener("DOMContentLoaded", () => {
    const bgCanvas = document.getElementById("galaksi-bg-canvas");
    if (bgCanvas) initMeshDriftBackground(bgCanvas);
    const root = document.getElementById("galaksi-specimen-root");
    if (root) initGalaksiSpecimen(root);
  });
})();
