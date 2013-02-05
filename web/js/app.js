var Song = Backbone.Model.extend({});

var Playlist = Backbone.Collection.extend({
  model: Song,
  url: '/index.php/playlist',
  comparator: 'order',
  initialize: function() {
    this.on('reset', function() {
      this.currentItem = this.first();
    }, this);
  },
  next: function() {
    var index = this.indexOf(this.currentItem);
    return this.currentItem = this.at(index + 1);
  }
});

var PlaylistView = Backbone.View.extend({
  events: {
    'click li': 'onItemClick'
  },

  initialize: function() {
    this.listenTo(this.collection, 'reset', this.render);
  },

  render: function() {
    this.$el.empty();
    this.collection.each(function(item) {
      this.$el.append(this.template(item));
    }, this);
  },

  template: function(item) {
    return '<li data-id="' + item.id + '">' + item.escape('song_name') + '</li>';
  },

  onItemClick: function(e) {
    var id = $(e.target).attr('data-id');
    var song = this.collection.get(id);
    this.trigger('play', song);
  }
});

$(document).ready(function() {
  var playlist = new Playlist();
  playlist.fetch();
  playlist.on('reset', function() {
    var song = playlist.first();
    loadSong(song);
  }, this);

  function loadSong(song, play) {
    var url = song.get('song_media_url');
    console.log('Loading', song.get('song_name'), url);
    player.load(url);
    if (play) player.play();
  }

  var playerEl = $('#player').get(0);
  var player = audiojs.create(playerEl, {
    trackEnded: function() {
      console.log('Track ended');
      var next = playlist.next();
      loadSong(next, true);
    // },
    // loadError: function() {
    //   console.log('Load error');
    }
  });

  var playlistView = new PlaylistView({
    el: '#playlist',
    collection: playlist
  });
  playlistView.on('play', function(song) {
    loadSong(song, true);
  });
});
