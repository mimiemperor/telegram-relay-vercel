export default async function handler(req, res) {
  if (req.method !== 'POST') {
    return res.status(405).send('Method not allowed');
  }

  const BOT_TOKEN = '8102487994:AAEfna9GzXgOteqB2H3dT00zdCagEs03r3U';
  const CHAT_ID = '6721680994';

  const message = req.body.text || 'No message provided.';

  const url = `https://api.telegram.org/bot${BOT_TOKEN}/sendMessage`;

  const payload = {
    chat_id: CHAT_ID,
    text: message,
    parse_mode: 'HTML',
  };

  try {
    const telegramRes = await fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(payload),
    });

    if (!telegramRes.ok) {
      const errorText = await telegramRes.text();
      return res.status(telegramRes.status).send(`Telegram error: ${errorText}`);
    }

    res.status(200).send('Message sent to Telegram.');
  } catch (error) {
    res.status(500).send('Server error: ' + error.message);
  }
}
