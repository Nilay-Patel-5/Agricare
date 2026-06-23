const mongoose = require('mongoose');
mongoose.connect('mongodb://localhost:27017/agricare').then(async () => {
  const ChatMessage = require('./src/models/ChatMessage');
  const reqUserId = '6a37f3aa2b253723efbe2488';
  const history = await ChatMessage.aggregate([
    { $match: { user_id: new mongoose.Types.ObjectId(reqUserId), role: 'user' } },
    { $sort: { createdAt: 1 } },
    { $group: {
        _id: '$session_key',
        title: { $first: '$message' },
        createdAt: { $first: '$createdAt' }
    }},
    { $sort: { createdAt: -1 } }
  ]);
  const nullDates = history.filter(h => !h.createdAt);
  console.log('Null dates:', nullDates);
  console.log('Total history:', history.length);
  process.exit(0);
});
